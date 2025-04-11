<?php

namespace App\Services\Impl;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\FieldOpeningHoursRequest;
use App\Repository\IFieldOpeningHoursRepository;
use App\Repository\IFieldRepository;
use App\Repository\IVenueRepository;
use App\Services\IFieldOpeningHoursService;

class FieldOpeningHoursService implements IFieldOpeningHoursService
{
    private IFieldRepository $fieldRepository;
    private IVenueRepository $venueRepository;
    private IFieldOpeningHoursRepository $fieldOpeningHoursRepository;

    public function __construct(IFieldRepository $fieldRepository, IFieldOpeningHoursRepository $fieldOpeningHoursRepository, IVenueRepository $venueRepository){
        $this->fieldRepository = $fieldRepository;
        $this->fieldOpeningHoursRepository = $fieldOpeningHoursRepository;
        $this->venueRepository = $venueRepository;
    }


    /**
     * @throws \Exception
     */
    public function save(FieldOpeningHoursRequest $request): array
    {
        $userId = $request->user()->uuid;
        $fieldId = $request->get('field_id');
        $this->authorizeFieldAccess($fieldId, $userId);
        return $this->fieldOpeningHoursRepository->saveAll($request, $fieldId);
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function update(FieldOpeningHoursRequest $request): array
    {
        $userId = $request->user()->uuid;
        $fieldId = $request->get('field_id');
        $this->authorizeFieldAccess($fieldId, $userId);
        return $this->fieldOpeningHoursRepository->update($request, $fieldId);
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    private function authorizeFieldAccess(string $fieldId, string $userId): void
    {
        $field = $this->fieldRepository->getById($fieldId);
        if (!$field) {
            throw new NotFoundException("Field not found");
        }

        $venue = $this->venueRepository->getById($field->venue_id);
        if ($userId !== $venue->owner_id) {
            throw new UnauthorizedException("User not allowed to manage opening hours");
        }
    }

    public function getByFieldId($fieldId): array{
        return $this->fieldOpeningHoursRepository->getByFieldId($fieldId);
    }
}
