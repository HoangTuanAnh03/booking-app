<?php

namespace App\Repository;

use App\Http\Requests\CourtSpecialTimeRequest;

interface ICourtRepository
{
    public function show();
    public function getById(string $id);
    public function store(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
    public function getOwnerId(string $courtId);
    public function createSpecialTimes(CourtSpecialTimeRequest $request);
}
