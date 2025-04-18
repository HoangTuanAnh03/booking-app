<?php

namespace App\Repository\Impl;

use App\Models\Court;
use App\Models\CourtSlot;
use App\Repository\ICourtRepository;
use Illuminate\Support\Facades\DB;

class CourtRepository implements ICourtRepository
{
    public function show()
    {
        return Court::all()->toArray();
    }

    public function getById(string $id)
    {
        return Court::where('court_id', $id)->first();
    }

    public function store(array $data)
    {
        return Court::create($data);
    }

    public function update(array $data, string $id)
    {
        $court = Court::where('court_id', $id)->first();
        if (!$court) return null;

        $court->update($data);
        return $court;
    }

    public function delete(string $id)
    {
        $court = Court::where('court_id', $id)->first();
        if (!$court) return null;

        $court->delete();
        return $court;
    }

    public function getOwnerId(string $courtId){
        return DB::table('courts')
            ->join('fields', 'courts.field_id', '=', 'fields.field_id')
            ->join('venues', 'fields.venue_id', '=', 'venues.venue_id')
            ->where('courts.court_id', $courtId)
            ->value('venues.owner_id');
    }
}
