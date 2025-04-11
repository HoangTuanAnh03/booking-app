<?php

namespace App\Repository\Impl;

use App\Models\Venue;
use App\Repository\IVenueRepository;

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
}
