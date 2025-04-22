<?php
namespace App\Repository;

use Illuminate\Support\Collection;

interface IVenueImageRepository
{
    public function getAllByVenueId(string $venue_id): Collection;
    public function store(array $data);
    public function delete(int $image_id): bool;
    public function update(int $image_id, array $data);
}

