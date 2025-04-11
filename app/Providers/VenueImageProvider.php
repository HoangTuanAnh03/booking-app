<?php

namespace App\Providers;

use App\Repository\Impl\VenueImageRepository;
use App\Repository\IVenueImageRepository;
use App\Services\Impl\VenueImageService;
use App\Services\IVenueImageService;
use Illuminate\Support\ServiceProvider;

class VenueImageProvider extends ServiceProvider
{
    public function register():void
    {
        $this->app->bind(IVenueImageRepository::class, VenueImageRepository::class);
        $this->app->bind(IVenueImageService::class, VenueImageService::class);
    }

    public function boot(): void
    {
        //
    }
}
