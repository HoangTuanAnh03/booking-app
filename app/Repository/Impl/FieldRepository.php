<?php
namespace App\Repository\Impl;

use App\Models\Field;
use App\Repository\IFieldRepository;
use Illuminate\Support\Facades\DB;

class FieldRepository implements IFieldRepository
{
    public function show(int $perPage, string $id) {

        return Field::with('openingHourToday')
            ->where('venue_id', $id)
            ->paginate($perPage);
    }

    public function getById(string $id) {
        return Field::with('openingHoursWeek')->where('field_id', $id)->first();
    }

    public function store(array $data) {
        return Field::create($data);
    }

    public function update(array $data, string $id) {
        $Field = Field::where('field_id', $id)->first();
        if (!$Field) return null;

        $Field->update($data);
        return $Field;
    }

    public function delete(string $id) {
        $Field = Field::where('field_id', $id)->first();
        if (!$Field) return null;

        $Field->delete();
        return $Field;
    }

    public function getOwnerId(string $fieldId){
        return DB::table('fields')
            ->join('venues', 'fields.venue_id', '=', 'venues.venue_id')
            ->where('fields.field_id', $fieldId)
            ->value('venues.owner_id');
    }
}
