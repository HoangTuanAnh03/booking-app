<?php

namespace App\Services\Impl;

use App\Exceptions\ErrorException;
use App\Services\IMapService;
use Illuminate\Support\Facades\Http;

class MapService implements IMapService
{

    /**
     * @throws \Exception
     */
    public function getLatLngByName(string $address): array
    {
        $apiKey = config('services.goong.api_key');

        $url = 'https://rsapi.goong.io/geocode';
        $response = Http::get($url, [
            'address' => $address,
            'api_key' => $apiKey,
        ]);
        if (!$response->ok()) {
            throw new ErrorException("HTTP error! status: " . $response->status());
        }
        $data = $response->json();
        $firstLocation = $data['results'][0]['geometry']['location'] ?? null;

        if (!$firstLocation) {
            throw new ErrorException('Not found');
        }
        $lat = $firstLocation['lat'];
        $lng = $firstLocation['lng'];
        return $this->convertLatLng([$lat, $lng]);
    }

    public function convertLatLng(array $coords): array
    {
        [$lat, $lng] = $coords;
        if ($lat < -90 || $lat > 90) {
            return [$lng, $lat];
        }
        return [$lat, $lng];
    }
}
