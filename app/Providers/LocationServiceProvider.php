<?php

namespace App\Providers;

use App\Repository\ILocationServiceRepository;
use App\Repository\Impl\LocationServiceRepository;
use App\Repository\Impl\VenueRepository;
use App\Repository\IVenueRepository;
use App\Services\ILocationServiceService;
use App\Services\Impl\LocationServiceService;
use App\Services\Impl\VenueService;
use App\Services\IVenueService;
use Illuminate\Support\ServiceProvider;

class LocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ILocationServiceRepository::class, LocationServiceRepository::class);
        $this->app->bind(ILocationServiceService::class, LocationServiceService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
