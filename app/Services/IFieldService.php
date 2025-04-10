<?php
namespace App\Services;

use App\Http\Requests\FieldRequest;
use App\Models\Field;
use Illuminate\Http\Request;

/**
 * Interface IFieldService
 *
 * Defines the contract for managing Fields, including
 * retrieving, creating, updating, and deleting Fields.
 */
interface IFieldService
{
    /**
     * Get a paginated list of Fields.
     *
     * @param int $perPage Number of items per page
     * @return mixed Paginated list of Fields
     */
    public function show(int $perPage, string $id);

    /**
     * Find a Field by its ID.
     *
     * @param string $id Field UUID
     * @return Field|null Field object or null if not found
     */
    public function findById(string $id): ?Field;

    /**
     * Create and store a new Field.
     *
     * @param FieldRequest $request The validated request data
     * @return Field The created Field
     */
    public function add(FieldRequest $request): Field;

    /**
     * Update a Field by its ID.
     *
     * @param string $id Field UUID
     * @param FieldRequest $request The validated request data
     * @return Field|null The updated Field or null if not found
     */
    public function update(string $id, FieldRequest $request): ?Field;

    /**
     * Delete a Field by its ID.
     *
     * @param string $id Field UUID
     * @return Field|null The deleted Field or null if not found
     */
    public function delete(string $id, Request  $request): ?Field;
}
