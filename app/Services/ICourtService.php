<?php
 namespace App\Services;

 use App\Http\Requests\CourtRequest;
 use App\Http\Requests\CourtSpecialTimeRequest;
 use App\Models\Court;

 interface ICourtService
 {
    public function show(): array;

    public function findById(string $id): ?Court;

    public function add(CourtRequest $request): Court;

    public function update(string $id, CourtRequest $request): ?Court;

    public function delete(string $id): ?Court;

     /**
      * Creates special times for a court.
      *
      * @param CourtSpecialTimeRequest $request The court special time request object.
      * @return array The created special times details.
      */
     public function createSpecialTimes(CourtSpecialTimeRequest $request): array;
 }
