<?php
namespace App\Services\Impl;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\LocationServiceRequest;
use App\Models\LocationService;
use App\Repository\ILocationServiceRepository;
use App\Repository\IVenueRepository;
use App\Services\ILocationServiceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
class LocationServiceService implements ILocationServiceService
{
    private ILocationServiceRepository $repository;
    private IVenueRepository $venueRepository;

    public function __construct(ILocationServiceRepository $repository, IVenueRepository $venueRepository) {
        $this->repository = $repository;
        $this->venueRepository = $venueRepository;
    }

    public function show(int $perPage , string $id) {
        return $this->repository->show($perPage,$id);
    }

    public function findById(int $id): ?LocationService {
        return $this->repository->getById($id);
    }

    /**
     * @throws ForbiddenException
     */
    public function add(LocationServiceRequest $request): LocationService {
        $data = $this->validateOwnerAndExtractData($request);
        return $this->repository->store($data);
    }

    /**
     * @throws ForbiddenException
     */
    public function update(int $id, LocationServiceRequest $request): ?LocationService {
        $data = $this->validateOwnerAndExtractData($request);
        return $this->repository->update($data, $id);
    }

    public function delete(int $id, Request  $request): ?LocationService {
        $this->checkOwnerBeforeDelete($id, $request->user());
        return $this->repository->delete($id);
    }


    private function validateOwnerAndExtractData(LocationServiceRequest $request): array {
        $user = $request->user();
        $venue_id = $request->get('venue_id');
        $owner_id = $this->venueRepository->getById($venue_id)->owner_id;
        Log::info("owner: ".$owner_id."venue_id: ".$venue_id."user_id: ".$user->uuid);

        if ($owner_id != $user->uuid) {
            throw new ForbiddenException();
        }

        return [
            'venue_id' => $venue_id,
            'service_name' => $request->get('service_name'),
            'price' => $request->get('price'),
            'is_available' => $request->get('is_available'),
            'description' => $request->get('description'),
        ];
    }

    private function checkOwnerBeforeDelete(int $locationServiceId, $user): void {
        $locationService = $this->repository->getById($locationServiceId);
        $venue = $this->venueRepository->getById($locationService->venue_id);

        if ($venue->owner_id != $user->uuid) {
            throw new ForbiddenException();
        }
    }


}
