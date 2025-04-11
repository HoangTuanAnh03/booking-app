<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface IVenueImageService
{
    public function getAllByVenueId(string $venue_id): Collection;
    public function store(array $data);
    public function destroy(int $image_id): bool;
    public function updateThumbnail(int $image_id, array $data);
}
