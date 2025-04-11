<?php

namespace App\Services\Impl;

use App\Repository\IVenueImageRepository;
use App\Services\IVenueImageService;
use Illuminate\Support\Collection;

class VenueImageService implements IVenueImageService
{
    protected IVenueImageRepository $repository;

    public function __construct(IVenueImageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllByVenueId(string $venue_id): Collection
    {
        return $this->repository->getAllByVenueId($venue_id);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function destroy(int $image_id): bool
    {
        return $this->repository->delete($image_id);
    }

    public function updateThumbnail(int $image_id, array $data)
    {
        return $this->repository->updateThumbnail($image_id, $data);
    }
}
