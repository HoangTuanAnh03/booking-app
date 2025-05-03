<?php

namespace App\Services;

interface IMapService
{
    public function getLatLngByName(string $address): array;
    public function convertLatLng(array $coords): array;
}
