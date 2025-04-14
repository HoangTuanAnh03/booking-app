<?php

namespace App\Repository;

interface IBookingCourtRepository
{
    public function show();
    public function getById(string $id);
    public function store(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
}
