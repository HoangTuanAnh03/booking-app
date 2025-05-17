<?php

namespace App\Repository\Impl;

use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Models\Venue;
use App\Repository\IVenueRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

class VenueRepository implements IVenueRepository
{
    public function show(array $data)
    {
        return $this->getVenuesQuery($data)
            ->where('status', 'active')
            ->get()
            ->map(fn($venue) => $this->transformVenue($venue));
    }

    public function showForOwner(array $data)
    {
        $data['include_payment_status'] = true;
        return $this->getVenuesQuery($data)
            ->where('owner_id', $data['uid'])
            ->get()
            ->map(fn($venue) => $this->transformVenue($venue));
    }

    private function getVenuesQuery(array $data)
    {
//        return Venue::with('images')
//            ->when(!empty($data['name']), function ($query) use ($data) {
//                $query->where('name', 'like', '%' . $data['name'] . '%');
//            })
//            ->orderBy($data['sortBy'] ?? 'created_at', $data['sortDirection'] ?? 'desc')
//            ->skip((($data['page'] ?? 1) - 1) * ($data['limit'] ?? 10))
//            ->take($data['limit'] ?? 10);

        $query = Venue::with('images')->select('*');

        if (isset($data['include_payment_status']) && $data['include_payment_status']) {
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            $query->addSelect([
                'payment_status' => function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->select(DB::raw('CASE WHEN EXISTS (
                    SELECT 1
                    FROM venue_payment
                    WHERE venue_payment.venue_id = venues.venue_id
                    AND venue_payment.status = \'paid\'
                    AND venue_payment.created_at BETWEEN ? AND ?
                ) THEN 1 ELSE 0 END'))
                        ->addBinding([$startOfMonth, $endOfMonth], 'select');
                }
            ]);
        }

        return $query
            ->when(!empty($data['name']), function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->orderBy($data['sortBy'] ?? 'created_at', $data['sortDirection'] ?? 'desc')
            ->skip((($data['page'] ?? 1) - 1) * ($data['limit'] ?? 10))
            ->take($data['limit'] ?? 10);
    }

    private function transformVenue($venue)
    {
        $images = $venue->images->groupBy('type');

        $result = [
            'venue_id' => $venue->venue_id,
            'name' => $venue->name,
            'address' => $venue->address,
            'thumbnail' => $images->has('thumbnail') ? $images['thumbnail']->first()->image_url : null,
            'cover' => $images->has('cover') ? $images['cover']->first()->image_url : null,
            'created_at' => $venue->created_at,
            'updated_at' => $venue->updated_at,
            'status' => $venue->status,
        ];

        if (isset($venue->payment_status)) {
            $result['payment_status'] = (bool) $venue->payment_status;
        }

        return $result;
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

    public function venueForMap(): Collection
    {
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
        $venue = Venue::with(['owner', 'fields.openingHourToday', 'images'])
            ->where('venue_id', $venueId)
            ->firstOrFail();

        return $this->formatVenueDetail($venue);
    }

    private function buildVenueQuery($query)
    {
        return $query
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
            ");
    }

    public function getVenueByUid(string $userId): Collection
    {
        if (!Str::isUuid($userId)) {
            throw new \InvalidArgumentException('Invalid UUID format.');
        }

        $user = User::where('uuid', $userId)->first();
        if (!$user || $user->role !== 'owner') {
            return collect([]);
        }

        $query = Venue::where('venues.owner_id', $userId);
        $query = $this->buildVenueQuery($query);

        return $query->get();
    }


    public function getVenues(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Venue::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('venues.name', 'like', '%' . $search . '%')
                    ->orWhere('venues.address', 'like', '%' . $search . '%');
            });
        }
        $query = $this->buildVenueQuery($query);
        return $query->paginate($perPage);
    }

    /**
     * @throws NotFoundException
     */
    public function activateVenue(string $venueId): Venue
    {
        $venue = Venue::where('venue_id', $venueId)->first();
        if ($venue == null) {
            throw new NotFoundException('Venue not found.');
        }
        $venue->update(['status' => 'active']);
        return $venue;
    }

    public function getVenueStas(): array
    {
        $venueCountsByStatus = Venue::select('status')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => Venue::where('status', $item->status)->count()];
            });

        $allStatuses = ['active', 'locked', 'banned'];
        return collect($allStatuses)->mapWithKeys(function ($status) use ($venueCountsByStatus) {
            return [$status => $venueCountsByStatus->get($status, 0)];
        })->all();
    }

    public function searchNearByLatLng($lat, $lng, $distance): Collection
    {
        $lng = (float) $lng;
        $lat = (float) $lat;

        $pointWKT = "POINT($lng $lat)";
        Log::info("diss", [$distance]);
        // Truy vấn các venue trong bán kính 4km
        $venues = DB::table('venues')
            ->select([
                'venue_id',
                'name',
                'address',
                'status',
                'latitude',
                'longitude',
                DB::raw("ROUND(ST_Distance_Sphere(coordinates, ST_GeomFromText('$pointWKT')) / 1000, 2) AS distance")
            ])
            ->whereRaw("ST_Distance_Sphere(coordinates, ST_GeomFromText(?)) <= $distance", [$pointWKT])
            ->orderBy('distance')
            ->get();

        return $venues->map(function ($venue) {
            $sportTypes = DB::table('fields')
                ->join('sport_types', 'fields.sport_type_id', '=', 'sport_types.sport_type_id')
                ->where('fields.venue_id', $venue->venue_id)
                ->distinct()
                ->select('sport_types.sport_type_id', 'sport_types.name')
                ->get();

            return [
                'venue_id' => $venue->venue_id,
                'venue_name' => $venue->name,
                'latitude' => $venue->latitude,
                'longitude' => $venue->longitude,
                'address' => $venue->address,
                'distance' => $venue->distance,
                'sport_types' => $sportTypes->map(function ($sportType) {
                    return [
                        'id' => $sportType->sport_type_id,
                        'name' => $sportType->name,
                    ];
                }),
            ];
        });
    }

    public function searchNearByLatLngForHome($lat, $lng, $distance): Collection
    {
        $lng = (float) $lng;
        $lat = (float) $lat;

        $pointWKT = "POINT($lng $lat)";

        $venues = DB::table('venues')
            ->join('users', 'venues.owner_id', '=', 'users.uuid')
            ->select([
                'venues.venue_id',
                'venues.name',
                'venues.address',
                'venues.status',
                'venues.latitude',
                'venues.longitude',
                'users.phone_number',
                DB::raw("ROUND(ST_Distance_Sphere(coordinates, ST_GeomFromText('$pointWKT')) / 1000, 2) AS distance")
            ])
            ->whereRaw("ST_Distance_Sphere(coordinates, ST_GeomFromText(?)) <= $distance", [$pointWKT])
            ->orderBy('distance')
            ->get();

        return $venues->map(function ($venue) {
            $venueModel = Venue::with(['fields.openingHourToday', 'images'])
                ->where('venue_id', $venue->venue_id)
                ->first();

            return $this->formatVenueBasicInfo($venueModel, [
                'latitude' => $venue->latitude,
                'longitude' => $venue->longitude,
                'distance' => $venue->distance,
                'phone_number' => $venue->phone_number,
            ]);
        });
    }

    private function formatVenueDetail($venue): array
    {
        $openingHours = $venue->fields->pluck('openingHourToday')->filter();

        $earliestOpening = $openingHours->min('opening_time');
        $latestClosing = $openingHours->max('closing_time');

        $images = $venue->images->groupBy('type')->mapWithKeys(function ($group, $type) {
            if ($type !== 'default') {
                return [$type => $group->first()->image_url ?? null];
            }
            return [$type => $group->pluck('image_url')->toArray()];
        })->toArray();

        return [
            'venue_id' => $venue->venue_id,
            'venue_name' => $venue->name,
            'venue_address' => $venue->address,
            'status' => $venue->status,
            'phone_number' => $venue->owner?->phone_number,
            'opening' => $earliestOpening,
            'closing' => $latestClosing,
            'images' => [
                'thumbnail' => $images['thumbnail'] ?? null,
                'cover' => $images['cover'] ?? null,
                'default' => $images['default'] ?? []
            ]
        ];
    }

    private function formatVenueBasicInfo($venue, $extra = []): array
    {
        $data = $this->formatVenueDetail($venue);

        $sportTypes = DB::table('fields')
            ->join('sport_types', 'fields.sport_type_id', '=', 'sport_types.sport_type_id')
            ->where('fields.venue_id', $venue->venue_id)
            ->distinct()
            ->select('sport_types.sport_type_id', 'sport_types.name')
            ->get()
            ->map(fn($sportType) => [
                'id' => $sportType->sport_type_id,
                'name' => $sportType->name,
            ]);

        return array_merge($data, [
            'latitude' => $extra['latitude'] ?? null,
            'longitude' => $extra['longitude'] ?? null,
            'distance' => $extra['distance'] ?? null,
            'sport_types' => $sportTypes,
            'phone_number' => $extra['phone_number'] ?? ($data['phone_number'] ?? null),
        ]);
    }

    public function countVenuesByOwner($ownerId): int{
        return DB::table('venues')
            ->where('owner_id', $ownerId)
            ->count();
    }

    public function countActiveVenuesByOwner($ownerId): int{
        return DB::table('venues')
            ->where('owner_id', $ownerId)
            ->where('status', 'active')
            ->count();
    }
}
