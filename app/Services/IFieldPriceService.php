<?php

namespace App\Services;

use App\Http\Requests\FieldPriceRequest;
use Illuminate\Support\Collection;

interface IFieldPriceService
{
    /**
     * Save the field price for a specific field.
     *
     * @param $request
     * @param string $fieldId
     * @return void
     */
    public function save(FieldPriceRequest $request, string $fieldId): void;


    /**
     * Get the price for a specific field on a specific day of the week.
     *
     * @param string $fieldId
     * @param string|null $dayOfWeek
     * @return array
     */
    public function get(string $fieldId, ?string $dayOfWeek): array;
}
