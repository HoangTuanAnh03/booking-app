<?php

namespace App\Repository;

interface ICourtRepository
{
    public function show();
    public function getById(string $id);
    public function store(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
    public function getOwnerId(string $courtId);
}
