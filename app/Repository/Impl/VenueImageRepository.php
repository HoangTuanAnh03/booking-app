<?php

namespace App\Repository\Impl;

use App\Models\VenueImage;
use App\Repository\IVenueImageRepository;
use Illuminate\Support\Collection;

class VenueImageRepository implements IVenueImageRepository
{
    public function getAllByVenueId(string $venue_id): Collection
    {
        return VenueImage::where('venue_id', $venue_id)->get();
    }

    public function getById(int $id): ?VenueImage
    {
        return VenueImage::where('image_id', $id)->first();
    }

    /**
     * @throws \Exception
     */
    public function store(array $data)
    {
        if (in_array($data['type'], ['cover', 'thumbnail'])) {
            if (VenueImage::where('venue_id', $data['venue_id'])
                ->where('type', $data['type'])
                ->exists()) {
                return null;
            }
        }

        return VenueImage::create($data);
    }

    public function delete(int $image_id): bool
    {
        return VenueImage::where('image_id', $image_id)->delete();
    }

    public function update(int $image_id, array $data)
    {
        $image = VenueImage::findOrFail($image_id);
        $image->update($data);
        return $image;
    }
}
