<?php

namespace App\Repository\Impl;

use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Models\Venue;
use App\Repository\IVenueRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

class VenueRepository implements IVenueRepository
{
    public function show(array $data)
    {
        return Venue::when(!empty($data['name']), function ($query) use ($data) {
            $query->where('name', 'like', '%' . $data['name'] . '%');
        })
        ->orderBy($data['sortBy'] ?? 'created_at', $data['sortDirection'] ?? 'desc')
        ->skip((($data['page'] ?? 1) - 1) * ($data['limit'] ?? 10))
        ->take($data['limit'] ?? 10)
        ->get();
    }

    public function getById(string $id)
    {
        return Venue::where('venue_id', $id)->first();
    }

    public function store(array $data)
    {
        return Venue::create($data);
    }

    public function update(array $data, string $id)
    {
        $venue = Venue::where('venue_id', $id)->first();
        if (!$venue) return null;

        $venue->update($data);
        return $venue;
    }

    public function delete(string $id)
    {
        $venue = Venue::where('venue_id', $id)->first();
        if (!$venue) return null;

        $venue->delete();
        return $venue;
    }

    public function venueForMap(): Collection{
        return Venue::with('fields.sportType')
            ->get()
            ->map(function ($venue) {
                $sportTypes = [];

                foreach ($venue->fields as $field) {
                    if ($field->sportType) {
                        $sportTypes[] = [
                            'id' => $field->sportType->sport_type_id,
                            'name' => $field->sportType->name,
                        ];
                    }
                }

                // Loại bỏ trùng lặp theo 'id'
                $uniqueSportTypes = collect($sportTypes)->unique('id')->values();

                return [
                    'venue_id' => $venue->venue_id,
                    'venue_name' => $venue->name,
                    'latitude' => $venue->latitude,
                    'longitude' => $venue->longitude,
                    'address' => $venue->address,
                    'sport_types' => $uniqueSportTypes,
                ];
            });

    }

    public function getVenueDetail(string $venueId): array
    {
        $venue = Venue::with(['owner', 'fields.openingHourToday'])
            ->where('venue_id', $venueId)
            ->firstOrFail();

        $openingHours = $venue->fields
            ->pluck('openingHourToday')
            ->filter();

        $earliestOpening = $openingHours->min('opening_time');
        $latestClosing = $openingHours->max('closing_time');

        return [
            'venue_id' => $venue->venue_id,
            'venue_name' => $venue->name,
            'venue_address' => $venue->address,
            'phone_number' => $venue->owner?->phone_number,
            'opening' => $earliestOpening,
            'closing' => $latestClosing,
        ];
    }

    public function getVenueByUid(string $userId): Collection{
        if (!Str::isUuid($userId)) {
            throw new \InvalidArgumentException('Invalid UUID format.');
        }

        $user = User::where('uuid', $userId)->first();
        if (!$user) {
            return collect([]);
        }
        if ($user->role !== 'owner') {
            return collect([]);
        }

        return Venue::where('venues.owner_id', $userId)
            ->leftJoin('venue_payment', function ($join) {
                $join->on('venues.venue_id', '=', 'venue_payment.venue_id')
                    ->whereIn('venue_payment.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('venue_payment')
                            ->groupBy('venue_id');
                    });
            })
            ->select(
                'venues.venue_id',
                'venues.name',
                'venues.address',
                'venues.status',
                DB::raw("
                    CASE
                        WHEN venues.status = 'locked' THEN 'Sân chưa xác thực'
                        WHEN venues.status = 'banned' THEN 'Sân bị khóa'
                        WHEN venue_payment.status = 'paid' THEN 'Đã thanh toán'
                        ELSE 'Chưa thanh toán'
                    END AS payment_status
                ")
            )
            ->orderByRaw("
                CASE venues.status
                    WHEN 'locked' THEN 1
                    WHEN 'active' THEN 2
                    WHEN 'banned' THEN 3
                END ASC,
                CASE
                    WHEN venues.status = 'active' AND (venue_payment.status IS NULL OR venue_payment.status != 'paid') THEN 1
                    WHEN venues.status = 'active' AND venue_payment.status = 'paid' THEN 2
                    ELSE 3
                END ASC,
                venues.created_at DESC
            ")
            ->get();
    }

    /**
     * @throws NotFoundException
     */
    public function activateVenue(string $venueId) : Venue{
        $venue = Venue::where('venue_id', $venueId)->first();
        if ($venue == null) {
            throw new NotFoundException('Venue not found.');
        }
        $venue->update(['status' => 'active']);
        return $venue;
    }
}
