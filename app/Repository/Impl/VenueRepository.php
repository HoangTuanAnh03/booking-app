<?php

namespace App\Repository\Impl;

use App\Models\Venue;
use App\Repository\IVenueRepository;
use Illuminate\Support\Collection;

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
        return Venue::with(['fields.sportType'])
            ->get()
            ->map(function ($venue) {
                return [
                    'venue_id' => $venue->venue_id,
                    'venue_name' => $venue->name,
                    'latitude' => $venue->latitude,
                    'longitude' => $venue->longitude,
                    'sport_types' => $venue->fields->pluck('sportType.name')->unique()->values(),
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
}
