<?php

namespace App\Services\Impl;

use App\Exceptions\ErrorException;
use App\Http\Requests\PaginatingDataVenueRequest;
use App\Http\Requests\VenueFormRequest;
use App\Models\User;
use App\Models\Venue;
use App\Repository\IVenueRepository;
use App\Services\IVenueService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;

class VenueService implements IVenueService
{
    private IVenueRepository $repository;

    public function __construct(IVenueRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show(PaginatingDataVenueRequest $request): Collection
    {
        $data = [
            'name' => $request->query('name', ''),
            'page' => (int) $request->query('page', 1),
            'limit' => (int) $request->query('limit', 10),
            'sortBy' => $request->query('sortBy', 'created_at'),
            'sortDirection' => $request->query('sortDirection', 'desc')
        ];
        return $this->repository->show($data);
    }

    public function findById(string $id): ?Venue
    {
        return $this->repository->getById($id);
    }

    /**
     * @throws ErrorException
     */
    public function add(VenueFormRequest $request): Venue
    {
        $lng = $request->get('longitude');
        $lat = $request->get('latitude');
        $data = [
            'venue_id' => Str::uuid(),
            'owner_id' => $request->user()->uuid,
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'longitude' => $lng,
            'latitude' => $lat,
            'bank_account_number' => $request->get('bank_account_number'),
            'bank_name' => $request->get('bank_name'),
            'status' => 'locked',
            'coordinates' => DB::raw("ST_GeomFromText('POINT($lng $lat)')"),
        ];
        return $this->repository->store($data);
    }

    public function update(string $id, VenueFormRequest $request): ?Venue
    {
        $data = [
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'longitude' => $request->get('longitude'),
            'latitude' => $request->get('latitude'),
            'coordinates' => new Point($request->get('latitude'), $request->get('longitude')),
            'bank_account_number' => $request->get('bank_account_number'),
            'bank_name' => $request->get('bank_name'),
        ];
        return $this->repository->update($data, $id);
    }

    public function updateStatus(string $id, array $data): ?Venue
    {
        return $this->repository->update($data, $id);
    }

    public function delete(string $id): ?Venue
    {
        return $this->repository->delete($id);
    }

    // public function searchByFilter(PaginatingDataVenueRequest $request)
    // {
    //     $data = [
    //         'name' => $request->query('name', ''),
    //         'page' => (int) $request->query('page', 1),
    //         'limit' => (int) $request->query('limit', 10),
    //         'sortBy' => $request->query('sortBy', 'created_at'),
    //         'sortDirection' => $request->query('sortDirection', 'desc')
    //     ];

    //     return $this->repository->searchByFilter($data);
    // }
    public function venueForMap(): Collection {
        return $this->repository->venueForMap();
    }

    public function getVenueDetail(string $venueId): array{
        return $this->repository->getVenueDetail($venueId);
    }

    public function getVenueByUid(string $userId): Collection{
        return $this->repository->getVenueByUid($userId);
    }

    public function activateVenue(string $venueId): Venue{
        return $this->repository->activateVenue($venueId);
    }
}
