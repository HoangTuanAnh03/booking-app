<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\FieldOpeningHoursRequest;
use Exception;

//use App\Models\FieldOpeningHours;


interface IFieldOpeningHoursService
{
    /**
     * @param FieldOpeningHoursRequest $request
     * @return array
     * @throws NotFoundException
     * @throws UnauthorizedException
     *
     * */

    public function save(FieldOpeningHoursRequest $request): array;

    /**
     * @param FieldOpeningHoursRequest $request
     * @return array
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function update(FieldOpeningHoursRequest $request): array;

    /**
     * @param $fieldId
     * @return array
     */
    public function getByFieldId($fieldId): array;
}
