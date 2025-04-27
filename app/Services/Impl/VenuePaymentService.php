<?php

namespace App\Services\Impl;

use App\Exceptions\ErrorException;
use App\Exceptions\NotFoundException;
use App\Exceptions\RecordExistsException;
use App\Exceptions\UnauthorizedException;
use App\Mail\PaymentReminderEmail;
use App\Models\VenuePayment;
use App\Repository\IUserRepository;
use App\Repository\IVenuePaymentRepository;
use App\Repository\IVenueRepository;
use App\Services\IVenuePaymentService;
use App\Traits\ApiResponse;
use App\Http\Requests\VenuePaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PayOS\PayOS;

class VenuePaymentService implements IVenuePaymentService
{
    use ApiResponse;
    private IVenuePaymentRepository $venuePaymentRepository;
    private IVenueRepository $venueRepository;

    private IUserRepository $userRepository;
    public function __construct(IVenuePaymentRepository $venuePaymentRepository, IVenueRepository $venueRepository, IUserRepository $userRepository){
        $this->venuePaymentRepository = $venuePaymentRepository;
        $this->venueRepository = $venueRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param VenuePaymentRequest $request
     * @param string $venue_id
     * @return JsonResponse
     * @throws NotFoundException
     * @throws RecordExistsException
     * @throws UnauthorizedException
     */
    function makePayment(VenuePaymentRequest $request, string $venue_id): \Illuminate\Http\JsonResponse
    {
        $venue = $this->venueRepository->getById($venue_id);

        if($venue == null){
            throw new NotFoundException("Venue Not Found");
        }

        if($venue->owner_id != $request->user()->uuid){
            throw new UnauthorizedException("Not The Owner");
        }
        if($venue->status == 'locked'){
            throw new ErrorException("Venue Locked");
        }
        if($this->venuePaymentRepository->existsPaidThisMonth($venue_id)){
            throw new RecordExistsException("Venue Payment Already Exists");
        }

        $returnUri = $request->query('return_url');
        Log::warning($returnUri);
        do {
            $code = Str::upper(Str::random(20));
        } while (DB::table('venue_payment')->where('code', $code)->exists());
        $data = [
            "owner_id" => $request->user()->uuid,
            "venue_id" => $venue_id,
            "amount" => 20000,
            "code" => $code,
            "message" => "TT $code"
        ];
        $orderCode = now()->timestamp;

        $body = [
            'orderCode' => $orderCode,
            'amount' => $data['amount'],
            'description' => $data['message'],
            'returnUrl' => $returnUri,
            'cancelUrl' => $returnUri,
            'items' => [
                [
                    'name' => 'Thanh toán sân #' . $data['venue_id'],
                    'quantity' => 1,
                    'price' => $data['amount']
                ]
            ],
        ];
        $payOs = new PayOS(env('CLIENT_ID'), env('API_KEY'), env('CHECKSUM_KEY'));
        try {
            $response = $payOs->createPaymentLink($body);
            $this->venuePaymentRepository->store($data);
            return response()->json([
                'checkoutUrl' => $response['checkoutUrl'],
                'orderCode' => $orderCode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không tạo được link thanh toán',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param array $payload
     * @return void
     */
    public function handleWebhook(array $payload): void
    {
        if (!isset($payload['success']) || !$payload['success']) {
            Log::warning("🚫 Webhook không thành công", $payload);
            return;
        }
        $data = $payload['data'] ?? [];
        $orderCode = $data['orderCode'] ?? null;
        $amount = $data['amount'] ?? 0;
        $description = $data['description'] ?? null;
        $array = explode(' ', $description);
        $code = end($array);
        VenuePayment::where('code', $code)->update(['status' => 'paid']);
        Log::info("💰 Đã xác nhận thanh toán cho order #$orderCode với số tiền $amount VNĐ");
    }


    /**
     * @param VenuePaymentRequest $request
     * @return Collection|array
     */
    public function getAllVenueByOwnerId(VenuePaymentRequest $request) : Collection|array{
        $uid = $request->user()->uuid;
        return $this->venuePaymentRepository->getAllVenueByOwnerId($uid);
    }

    public function paymentRemiderEmail(): void
    {
        $venues = $this->venuePaymentRepository->getAllVenueByOwnerId();
        foreach ($venues as $venue) {
            $user = $this->userRepository->getById($venue->owner_id);

            $this->sendPaymentRemiderEmail($user->email, $user->name, $venue->name);
        }
    }

    public function unpaidVenueLocking(): void
    {
        $venues = $this->venuePaymentRepository->getAllVenueByOwnerId();
        foreach ($venues as $venue) {
            $venue->status = 'banned';
            $venue->save();
        }
    }

    public function getTotalRevenue(): array{
        return $this->venuePaymentRepository->getTotalRevenue();
    }

    private function sendPaymentRemiderEmail(string $email, string $ownerName, string $venueName): void
    {
        Mail::to($email)->send(new PaymentReminderEmail($ownerName, $venueName));
    }
}
