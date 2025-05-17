<?php
namespace App\Services\Impl;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\FieldRequest;
use App\Models\Field;
use App\Repository\IFieldRepository;
use App\Repository\IVenueRepository;
use App\Services\IFieldService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class FieldService implements IFieldService
{
    private IFieldRepository $repository;
    private IVenueRepository $venueRepository;

    public function __construct(IFieldRepository $repository, IVenueRepository $venueRepository) {
        $this->repository = $repository;
        $this->venueRepository = $venueRepository;
    }

    public function show(int $perPage , string $id) {
        return $this->repository->show($perPage,$id);
    }

    public function findById(string $id): ?Field {
        return $this->repository->getById($id);
    }

    /**
     * @throws ForbiddenException
     */
    public function add(FieldRequest $request): Field {
        $data = $this->validateOwnerAndExtractData($request);
        $data["field_id"] = Str::uuid();
        $data["is_active"] = true;
        return $this->repository->store($data);
    }

    /**
     * @throws ForbiddenException
     */
    public function update(string $id, FieldRequest $request): ?Field {
        $data = $this->validateOwnerAndExtractData($request);
        $data["is_active"] = $request->get('is_active');
        return $this->repository->update($data, $id);
    }

    /**
     * @throws ForbiddenException
     */
    public function delete(string $id, Request $request): ?Field {
        $this->checkOwnerBeforeDelete($id, $request->user());
        return $this->repository->delete($id);
    }


    /**
     * @throws ForbiddenException
     */
    private function validateOwnerAndExtractData(FieldRequest $request): array {
        $user = $request->user();
        $venue_id = $request->get('venue_id');
        $owner_id = $this->venueRepository->getById($venue_id)->owner_id;

        if ($owner_id != $user->uuid) {
            throw new ForbiddenException();
        }

        return [
            'venue_id' => $venue_id,
            'sport_type_id' => $request->get('sport_type_id'),
            'field_name' => $request->get('field_name'),
            'default_price' => $request->get('default_price'),
        ];
    }

    /**
     * @throws ForbiddenException
     */
    private function checkOwnerBeforeDelete(string $FieldId, $user): void {
        $Field = $this->repository->getById($FieldId);
        $venue = $this->venueRepository->getById($Field->venue_id);

        if ($venue->owner_id != $user->uuid) {
            throw new ForbiddenException();
        }
    }

    public function getCourtsByFieldAndDate(string $fieldId, string $date): array{
        return $this->repository->getCourtsByFieldAndDate($fieldId, $date);
    }

    public function getFieldStas(): array{
        return $this->repository->getFieldStas();
    }

    public function getTotalField(Request $request){
        return $this->repository->getTotalField($request->user()->uuid);
    }
}
