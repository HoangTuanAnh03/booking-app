<?php
namespace App\Repository\Impl;

use App\Models\LocationService;
use App\Repository\ILocationServiceRepository;

class LocationServiceRepository implements ILocationServiceRepository
{
    public function show(int $perPage) {
        return LocationService::paginate($perPage);
    }

    public function getById(int $id) {
        return LocationService::where('LocationService_id', $id)->first();
    }

    public function store(array $data) {
        return LocationService::create($data);
    }

    public function update(array $data, int $id) {
        $LocationService = LocationService::where('LocationService_id', $id)->first();
        if (!$LocationService) return null;

        $LocationService->update($data);
        return $LocationService;
    }

    public function delete(int $id) {
        $LocationService = LocationService::where('LocationService_id', $id)->first();
        if (!$LocationService) return null;

        $LocationService->delete();
        return $LocationService;
    }
}
