<?php
namespace App\Repository\Impl;

use App\Models\Field;
use App\Repository\IFieldRepository;

class FieldRepository implements IFieldRepository
{
    public function show(int $perPage, string $id) {

        return Field::where('venue_id', $id)->paginate($perPage);
    }

    public function getById(string $id) {
        return Field::where('field_id', $id)->first();
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
}
