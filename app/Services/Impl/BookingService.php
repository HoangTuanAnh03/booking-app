<?php

namespace App\Services\Impl;

use App\Exceptions\ErrorException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\CourtLockingRequest;
use App\Http\Requests\CourtSlotCheckingRequest;
use App\Mail\PaymentNotificationEmail;
use App\Models\Booking;
use App\Models\bookingCourt;
use App\Models\CourtSpecialTime;
use App\Models\Field;
use App\Models\Venue;
use App\Repository\IBookingCourtRepository;
use App\Repository\IBookingRepository;
use App\Repository\ICourtRepository;
use App\Repository\ICourtSlotRepository;
use App\Repository\IFieldRepository;
use App\Repository\IPaymentRepository;
use App\Repository\IUserRepository;
use App\Repository\IVenueRepository;
use App\Services\IBookingService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class BookingService implements IBookingService
{
    private IbookingRepository $repository;
    private ICourtSlotRepository $courtSlotRepository;
    private IPaymentRepository $paymentRepository;
    private IFieldRepository $fieldRepository;
    private IVenueRepository $venueRepository;
    private IUserRepository $userRepository;
    private IBookingCourtRepository $bookingCourtRepository;
    private ICourtRepository $courtRepository;

    public function __construct(IbookingRepository $bookingRepository, ICourtSlotRepository $courtSlotRepository, IFieldRepository $fieldRepository, IVenueRepository $venueRepository, IPaymentRepository $paymentRepository, IUserRepository $userRepository, IBookingCourtRepository $bookingCourtRepository, ICourtRepository $courtRepository)
    {
        $this->repository = $bookingRepository;
        $this->courtSlotRepository = $courtSlotRepository;
        $this->paymentRepository = $paymentRepository;
        $this->fieldRepository = $fieldRepository;
        $this->venueRepository = $venueRepository;
        $this->userRepository = $userRepository;
        $this->bookingCourtRepository = $bookingCourtRepository;
        $this->courtRepository = $courtRepository;
    }

    public function createBooking(BookingRequest $request): array
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->uuid;
        $bookingDate = Carbon::parse($data['booking_date']);
        $dayOfWeek = $bookingDate->format('l');

        return DB::transaction(function () use ($data, $dayOfWeek, $bookingDate) {
            $field = null;
            $totalPrice = 0;
            $validatedSlots = [];

            $courtSpecialTimes = CourtSpecialTime::whereIn('court_id', array_keys($data['court']))
                ->where('date', $bookingDate->format('Y-m-d'))
                ->get()
                ->groupBy('court_id')
                ->map(fn($courtTimes) => $courtTimes->keyBy('start_time'));

            foreach ($data['court'] as $courtId => $timeSlots) {
                $courtSpecialTime = $courtSpecialTimes->get($courtId, collect());
                foreach ($timeSlots as $slot) {
                    $startTime = $this->toCarbonTime('H:i', $slot['start_time']);
                    $endTime = $this->toCarbonTime('H:i', $slot['end_time']);

                    $bookingDateTimeEnd = $bookingDate->copy()->setTimeFrom($endTime);
                    if ($bookingDateTimeEnd->lt(now()->addMinutes(30))) {
                        throw new ErrorException("Cannot book a court that ends in the past (End time: {$bookingDateTimeEnd->format('Y-m-d H:i:s')})");
                    }

                    $duration = $startTime->diffInMinutes($endTime);
                    if ($duration <= 0) {
                        throw new ErrorException("$startTime after $endTime ??????");
                    }

                    $courtSlotCheck = [
                        'court_id' => $courtId,
                        'booking_date' => $bookingDate->format('Y-m-d'),
                        'start_time' => $startTime->format('H:i:s'),
                        'end_time' => $endTime->format('H:i:s')
                    ];

                    if ($this->courtSlotRepository->checkCourtSlotLock($courtSlotCheck)) {
                        throw new ErrorException("The requested time slot for court $courtId is already booked or locked by the owner.");
                    }

                    // Khung giờ được ghép
                    if ($courtSpecialTime->has($startTime->format('H:i:s'))) {
                        $courtSpecialTimeData = $courtSpecialTime->get($startTime->format('H:i:s'));
                        if ($courtSpecialTimeData->end_time != $endTime->format('H:i:s')) {
                            throw new ErrorException("Time period mismatch");
                        }
                        $minRental = $courtSpecialTimeData->min_rental;
                        $price = $courtSpecialTimeData->price;
                    } else {
                        // Tìm field price
                        $fieldPrice = $this->repository->findFieldPrice(
                            $data['field_id'],
                            $dayOfWeek,
                            $startTime->format('H:i:s'),
                            $endTime->format('H:i:s')
                        );

                        // Tồn tại trong field price
                        if ($fieldPrice) {

                            $price = $fieldPrice->price;
                            $minRental = $fieldPrice->min_rental;
                            $fieldStartTime = $this->toCarbonTime('H:i:s', $fieldPrice->start_time);
                            $fieldEndTime = $this->toCarbonTime('H:i:s', $fieldPrice->end_time);

                        } else {
                            // Không tồn tại trong field price => default price / 30p
                            $field = $field ?? Field::findOrFail($data['field_id']);

                            $fieldOpeningHours = DB::table('field_opening_hours')
                                ->where('field_id', $data['field_id'])
                                ->where('day_of_week', $bookingDate->format('l')) // Lấy ngày trong tuần
                                ->first();

                            if (!$fieldOpeningHours) {
                                throw new ErrorException("Field opening hours not found for the selected day.");
                            }

                            $fieldStartTime = $this->toCarbonTime('H:i:s', $fieldOpeningHours->opening_time);
                            $fieldEndTime = $this->toCarbonTime('H:i:s', $fieldOpeningHours->closing_time);

                            $price = $field->default_price;
                            $minRental = 30;

                        }
                        $this->checkRentalConditions($startTime, $endTime, $minRental, $fieldStartTime, $fieldEndTime);
                    }

                    $slotPrice = ($duration / $minRental) * $price;
                    $totalPrice += $slotPrice;
                    $validatedSlots[] = [
                        'court_id' => $courtId,
                        'start_time' => $startTime->format('H:i:s'),
                        'end_time' => $endTime->format('H:i:s'),
                        'price' => $slotPrice,
                        'start_carbon' => $startTime,
                        'end_carbon' => $endTime,
                        'min_rental' => $minRental,
                    ];
                }
            }

            // Create booking
            $booking = $this->repository->createBooking([
                'field_id' => $data['field_id'],
                'user_id' => $data['user_id'],
                'total_price' => $totalPrice,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'booking_date' => $data['booking_date'],
            ]);


            foreach ($validatedSlots as $slot) {
                $bookingCourt = $this->repository->createBookingCourt([
                    'booking_id' => $booking->booking_id,
                    'court_id' => $slot['court_id'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'price' => $slot['price'],
                ]);
                $currentTime = $slot['start_carbon']->copy();


                while ($currentTime < $slot['end_carbon']) {
                    $nextTime = $currentTime->copy()->addMinutes($slot['min_rental']);
                    $dataSave = [
                        'court_id' => $slot['court_id'],
                        'booking_court_id' => $bookingCourt->booking_court_id,
                        'start_time' => $currentTime->format('H:i:s'),
                        'end_time' => $nextTime->format('H:i:s'),
                        'date' => $data['booking_date'],
                        'is_looked' => true,
                        'locked_by_owner' => false,
                    ];
                    $this->courtSlotRepository->createCourtSlot($dataSave);
                    $currentTime = $nextTime;
                }
            }
            $message = "Thanh Toan Don $booking->order_id";
            $this->paymentRepository->createPayment([
                'booking_id' => $booking->booking_id,
                'uid' => $data['user_id'],
                'amount' => $totalPrice,
                'message' => $message,
            ]);

            $field = $this->fieldRepository->getById($data['field_id']);
            $venue = $this->venueRepository->getById($field->venue_id);
            return [
                'booking_id' => $booking->booking_id,
                'total_price' => $totalPrice,
                'bank_name' => $venue->bank_name,
                'message' => $message,
                'bank_account' => $venue->bank_account_number,
                'qr_url' => "https://img.vietqr.io/image/$venue->bank_name-$venue->bank_account_number-compact2.jpg?amount=$totalPrice&addInfo=$message"
            ];
        });
    }

    /**
     * @throws ErrorException
     */
    private function checkRentalConditions($startTime, $endTime, $minRental, $fieldStartTime = null, $fieldEndTime = null): void
    {
        $duration = $startTime->diffInMinutes($endTime);

        // Kiểm tra nếu duration không chia hết cho min_rental
        if ($duration % $minRental !== 0) {
            throw new ErrorException("Duration $duration minutes is not divisible by min_rental $minRental");
        }

        // Kiểm tra gap từ start time đến field start time
        if ($fieldStartTime && $startTime) {
            $gapToStart = $startTime->diffInMinutes($fieldStartTime);
            if ($gapToStart % $minRental !== 0) {
                throw new ErrorException("Gap from {$fieldStartTime->format('H:i:s')} to {$startTime->format('H:i:s')} is not divisible by min_rental");
            }
        }

        // Kiểm tra gap từ end time đến field end time
        if ($fieldEndTime && $endTime) {
            $gapToEnd = $fieldEndTime->diffInMinutes($endTime);
            if ($gapToEnd % $minRental !== 0) {
                throw new ErrorException("Remaining gap from {$endTime->format('H:i:s')} to {$fieldEndTime->format('H:i:s')} is not divisible by min_rental");
            }
        }
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws ErrorException
     */
    public function confirmBooking(Request $request, $bookingId): array
    {
        $booking = $this->repository->findById($bookingId);
        if (!$booking) {
            throw new NotFoundException("Booking not found");
        }
        if ($booking->user_id !== $request->user()->uuid) {
            throw new UnauthorizedException("You don't have access to this page");
        }
        if ($booking->created_at < now()->subMinutes(30) && $booking->status !== 'completed') {
            $booking->update(['status' => 'cancelled']);
            throw new ErrorException("Payment is overdue");
        }
        if ($booking->status == 'confirmed') {
            throw new ErrorException("Booking has been confirmed before");
        }

        if ($booking->status == 'completed') {
            throw new ErrorException("Booking has been completed before");
        }
        $field = $this->fieldRepository->getById($booking->field_id);
        $venue = $this->venueRepository->getById($field->venue_id);
        $owner = $this->userRepository->getById($venue->owner_id);
        $bookingCourts = $this->bookingCourtRepository->getByBookingId($booking->booking_id);
        $grouped = $bookingCourts->groupBy('court_id')->map(function ($items, $courtId) {
            $courtName = $this->courtRepository->getById($courtId)->court_name;
            return [
                'court_name' => $courtName,
                'times' => $items->map(function ($item) {
                    $start = date('H:i', strtotime($item->start_time));
                    $end = date('H:i', strtotime($item->end_time));
                    return "$start - $end";
                })->toArray()
            ];
        })->values();
        $booking->update(["status" => "confirmed"]);
        Log::info("Send email to $owner->email");
        $this->sendPaymentEmail(
            $owner->email,
            $booking->customer_name,
            $booking->customer_phone,
            $field->field_name,
            $venue->name,
            $booking->total_price,
//            str_replace('-', '', $booking->booking_id),
            "Thanh Toan Don $booking->order_id",
            $booking->booking_date,
            $grouped
        );
        return [];
    }

    private function sendPaymentEmail(string $email, string $customerName, string $customerPhone, string $fieldName, string $venueName, string $price, string $message_data, string $date, Collection $group): void
    {
        Mail::to($email)->send(new PaymentNotificationEmail($customerName, $customerPhone, $fieldName, $venueName, $price, $message_data, $date, $group));
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function completeBooking(Request $request, $bookingId): array
    {
        $booking = $this->repository->findById($bookingId);
        if (!$booking) {
            throw new NotFoundException("Booking not found");
        }

        if ($request->user()->uuid !== $this->fieldRepository->getOwnerId($booking->field_id)) {
            throw new UnauthorizedException("You don't have access to this page");
        }
        $booking->update(["status" => "completed"]);
        $this->paymentRepository->update($bookingId, ["status" => "paid"]);
        return [];
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ErrorException
     */
    public function cancelBooking(Request $request, $bookingId): string
    {
        $booking = $this->repository->findById($bookingId);
        if (!$booking) {
            throw new NotFoundException("Booking not found");
        }
        $ownerId = $this->fieldRepository->getOwnerId($booking->field_id);
        $uid = $request->user()->uuid;
        $bookingUserId = $booking->user_id;
        if ($bookingUserId !== $uid && $ownerId !== $uid) {
            throw new UnauthorizedException("You don't have access to this page");
        }
        if ($booking->status !== 'pending') {
            throw new ErrorException("Booking cannot be canceled");
        }
        try {
            DB::transaction(function () use ($bookingId, $booking) {
                $booking->update(["status" => "cancelled"]);
                $this->paymentRepository->update($bookingId, ["status" => "failed"]);
                $this->courtSlotRepository->deleteCourtSlotsByBookingId($bookingId);
            });
            return "Booking has been cancelled";
        } catch (ErrorException $e) {
            throw new ErrorException("booking cannot be cancelled");
        }
    }

    public function isLock(CourtSlotCheckingRequest $request): bool
    {
        $data = [
            'court_id' => $request['court_id'],
            'booking_date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'start_time' => $this->toCarbonTime('H:i', $request['start_time'])->format('H:i:s'),
            'end_time' => $this->toCarbonTime('H:i', $request['end_time'])->format('H:i:s')
        ];
        return $this->courtSlotRepository->checkCourtSlotLock($data);
    }

    /**
     * @throws UnauthorizedException
     */
    public function lock(CourtLockingRequest $request): array
    {
        $userId = $request->user()->uuid;
        $ownerId = $this->fieldRepository->getOwnerId($request['field_id']);

        if ($ownerId !== $userId) {
            throw new UnauthorizedException("You don't have access to this page");
        }
        $data = $request->validated();
        return DB::transaction(function () use ($data) {
            foreach ($data['court'] as $courtId => $timeSlots) {
                foreach ($timeSlots as $slot) {
                    $startTime = $this->toCarbonTime('H:i', $slot['start_time']);
                    $endTime = $this->toCarbonTime('H:i', $slot['end_time']);
                    $duration = $startTime->diffInMinutes($endTime);
                    if ($duration <= 0) {
                        throw new ErrorException("$startTime after $endTime ??????");
                    }
                    $dataSave = [
                        'court_id' => $courtId,
                        'booking_court_id' => null,
                        'start_time' => $startTime->format('H:i:s'),
                        'end_time' => $endTime->format('H:i:s'),
                        'date' => $data['date'],
                        'locked_by_owner' => true,
                    ];
                    if (!$this->courtSlotRepository->courtSlotExists(
                        $dataSave['court_id'],
                        $dataSave['start_time'],
                        $dataSave['end_time'],
                        $dataSave['date'],
                    )) {
                        $this->courtSlotRepository->createCourtSlot($dataSave);
                    }
                }
            }
            return [];
        });
    }

    public function getUserBookingStats(Request $request): array
    {
        $userId = $request->user()->uuid;
        $bookings = $this->repository->getUserBookingStats($userId, 7);
        $thirtyMinutesAgo = now()->subMinutes(30);
        $totalCompletedPrice = 0;
        $mappedBookings = [];

        foreach ($bookings as $booking) {
            $status = $booking->status;
            if ($status === 'cancelled') {
                $displayStatus = 'Hủy';
            } elseif ($status === 'completed') {
                $displayStatus = 'Thành công';
                $totalCompletedPrice += $booking->total_price;
            } elseif (in_array($status, ['pending', 'confirmed'])) {
                if (Carbon::parse($booking->created_at)->lt($thirtyMinutesAgo)) {
                    $displayStatus = 'Quá hạn';
                } else {
                    $displayStatus = $status === 'pending' ? 'Chờ Thanh Toán' : 'Chờ Xác Nhận Bởi Chủ Sân';
                }
            } else {
                $displayStatus = $status;
            }

            $mappedBookings[] = [
                'booking_id' => $booking->booking_id,
                'booking_date' => Carbon::parse($booking->booking_date)->format('Y-m-d'),
                'total_price' => number_format($booking->total_price, 2),
                'status' => $displayStatus,
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'bookings' => $mappedBookings,
            'total_completed_price' => number_format($totalCompletedPrice, 2),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'next_page_url' => $bookings->nextPageUrl(),
                'prev_page_url' => $bookings->previousPageUrl(),
            ],
        ];
    }

    public function getOwnerBookingStats(Request $request): array
    {
        $ownerId = $request->user()->uuid;

        $fieldIds = Venue::where('owner_id', $ownerId)
            ->join('fields', 'venues.venue_id', '=', 'fields.venue_id')
            ->pluck('fields.field_id')
            ->toArray();
        if (empty($fieldIds)) {
            return [
                'bookings' => [],
                'total_completed_price' => '0.00',

            ];
        }

        $bookings = $this->repository->getOwnerBookingStats($fieldIds, 7);
        $totalCompletedPrice = 0;
        $mappedBookings = [];

        foreach ($bookings as $booking) {
            if ($booking->status === 'completed') {
                $totalCompletedPrice += $booking->total_price;
            }
            if (in_array($booking->status, ['pending', 'confirmed'])) {
                $createdAt = Carbon::parse($booking->created_at);
                if ($createdAt->diffInMinutes(now()) > 15) {
                    $booking->status = 'overdue';
                }
            }
            $courts = $booking->bookingCourts->map(function ($court) {
                return [
                    'court_id' => $court->court_id,
                    'court_name' => $court->court->court_name,
                    'start_time' => Carbon::parse($court->start_time)->format('H:i:s'),
                    'end_time' => Carbon::parse($court->end_time)->format('H:i:s'),
                    'price' => number_format($court->price, 2),
                ];
            });
            $mappedBookings[] = [
                'booking_id' => $booking->booking_id,
                'message' => "Thanh Toan Don $booking->order_id",
                'field_id' => $booking->field_id,
                'booking_date' => Carbon::parse($booking->booking_date)->format('Y-m-d'),
                'total_price' => number_format($booking->total_price, 2),
                'customer_name' => $booking->customer_name,
                'customer_phone' => $booking->customer_phone,
                'status' => $booking->status,
                'courts' => $courts,
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
            ];
        }
        return [
            'bookings' => $mappedBookings,
            'total_completed_price' => number_format($totalCompletedPrice, 2),
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
                'next_page_url' => $bookings->nextPageUrl(),
                'prev_page_url' => $bookings->previousPageUrl(),
            ],
        ];
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws ErrorException
     */
    public function getPaymentQRCode(Request $request, $bookingId): array
    {
        $userId = $request->user()->uuid;
        $booking = $this->repository->findById($bookingId);
        if ($booking == null) {
            throw new NotFoundException("Booking not found");
        }
        if ($booking->user_id !== $userId) {
            throw new UnauthorizedException("You don't have permission to access this page");
        }

        if ($booking->status === 'pending') {
            $field = $this->fieldRepository->getById($booking->field_id);
            $venue = $this->venueRepository->getById($field->venue_id);
            return [
                'booking_id' => $booking->booking_id,
                'total_price' => $booking->total_price,
                'bank_name' => $venue->bank_name,
                'bank_account' => $venue->bank_account_number,
                'qr_url' => "https://img.vietqr.io/image/$venue->bank_name-$venue->bank_account_number-compact2.jpg?amount=$booking->total_price&addInfo=$booking->booking_id"
            ];
        }
        throw new ErrorException("Booking already $booking->status");
    }

    public function getUserBookings(Request $request): array
    {
        $user = $request->user();
        return Booking::where('user_id', $user->uuid)
            ->with([
                'field.venue' => function ($query) {
                    $query->select('venue_id', 'name', 'address');
                },
                'bookingCourts.court' => function ($query) {
                    $query->select('court_id', 'court_name');
                },
                'bookingCourts.courtSlots' => function ($query) {
                    $query->select('slot_id', 'court_id', 'booking_court_id', 'start_time', 'end_time', 'date');
                }
            ])
            ->select('booking_id', 'field_id', 'user_id', 'status', 'created_at', 'total_price')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                $statusDisplay = $this->getBookingStatusDisplay($booking);
                $courtSlots = $booking->bookingCourts->flatMap(function ($bookingCourt) {
                    return $bookingCourt->courtSlots->map(function ($slot) use ($bookingCourt) {
                        return [
                            'court_id' => $bookingCourt->court->court_id,
                            'court_name' => $bookingCourt->court->court_name,
                            'start_time' => $slot->start_time,
                            'end_time' => $slot->end_time,
                            'date' => $slot->date,
                        ];
                    });
                });

                return [
                    'booking_id' => $booking->booking_id,
                    'total_price' => number_format($booking->total_price, 0),
                    'venue_name' => $booking->field->venue->name,
                    'venue_address' => $booking->field->venue->address,
                    'field_name' => $booking->field->field_name,
                    'court_slots' => $courtSlots,
                    'status' => $statusDisplay,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                ];
            })->toArray();
    }

    private function toCarbonTime(string $from, $data): ?Carbon
    {
        return Carbon::createFromFormat($from, $data);
    }

    private function getBookingStatusDisplay($booking): string
    {
        $createdAt = Carbon::parse($booking->created_at);
        $fifteenMinutesAgo = Carbon::now()->subMinutes(15);

        if ($booking->status === 'cancelled') {
            return 'Đã hủy';
        } elseif ($booking->status === 'completed') {
            return 'Đã xác nhận';
        } elseif ($booking->status === 'pending' && $createdAt->gt($fifteenMinutesAgo)) {
            return 'Chờ thanh toán';
        } elseif ($booking->status === 'confirmed' && $createdAt->gt($fifteenMinutesAgo)) {
            return 'Chờ chủ sân xác nhận';
        } else {
            return 'Hủy do quá thời gian';
        }
    }

    public function stats(Request $request): array
    {
        $owner = auth()->user();
        $month = $request->query('month');
        $year = $request->query('year', now()->year);

        $venues = Venue::where('owner_id', $owner->uuid)
            ->with(['fields.courts'])
            ->get();

        $result = [];

        foreach ($venues as $venue) {
            $totalVenueRevenue = 0;
            $fieldsData = [];

            foreach ($venue->fields as $field) {
                $totalFieldRevenue = 0;
                $courtsData = [];

                foreach ($field->courts as $court) {
                    $bookingCourts = BookingCourt::where('court_id', $court->court_id)
                        ->whereHas('booking', function ($q) use ($month, $year) {
                            $q->where('status', 'completed');
                            if ($month) {
                                $q->whereMonth('booking_date', $month);
                            }
                            if ($year) {
                                $q->whereYear('booking_date', $year);
                            }
                        });

                    $courtRevenue = $bookingCourts->sum('price');
                    $totalFieldRevenue += $courtRevenue;
                    $courtsData[] = [
                        'court_id' => $court->court_id,
                        'court_name' => $court->court_name,
                        'total_revenue' => (float) $courtRevenue
                    ];
                }

                $totalVenueRevenue += $totalFieldRevenue;
                $fieldsData[] = [
                    'field_id' => $field->field_id,
                    'field_name' => $field->field_name,
                    'total_revenue' => (float) $totalFieldRevenue,
                    'courts' => $courtsData
                ];
            }

            $result[] = [
                'venue_id' => $venue->venue_id,
                'name' => $venue->name,
                'total_revenue' => (float) $totalVenueRevenue,
                'fields' => $fieldsData
            ];
        }

        return [
            'total_revenue' => (float) array_sum(array_column($result, 'total_revenue')),
            'venues' => $result
        ];
    }

    public function getTop5VenuesByRevenue(Request $request){
        $owner = $request->user();
        $month = now()->month;
        $year = now()->year;
        return $this->repository->getTop5VenuesByRevenue($owner->uuid, $year, $month);
    }

    public function getTop5VenuesByBooking(Request $request){
        $owner = $request->user();
        $month = now()->month;
        $year = now()->year;

        return $this->repository->getTop5VenuesByBooking($owner->uuid, $year, $month);
    }
}
