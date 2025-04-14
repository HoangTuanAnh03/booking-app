<?php
 namespace App\Services;
 
 use App\Http\Requests\CourtRequest;
 use App\Models\Court;
 
 interface ICourtService
 {
    public function show(): array;

    public function findById(string $id): ?Court;

    public function add(CourtRequest $request): Court;

    public function update(string $id, CourtRequest $request): ?Court;

    public function delete(string $id): ?Court;
 }