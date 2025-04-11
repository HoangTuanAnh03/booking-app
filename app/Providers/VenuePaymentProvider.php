<?php

namespace App\Providers;

use App\Repository\Impl\VenuePaymentRepository;
use App\Repository\Impl\VenueRepository;
use App\Repository\IVenuePaymentRepository;
use App\Repository\IVenueRepository;
use App\Services\Impl\VenuePaymentService;
use App\Services\Impl\VenueService;
use App\Services\IVenuePaymentService;
use App\Services\IVenueService;
use Illuminate\Support\ServiceProvider;

class VenuePaymentProvider extends ServiceProvider
{
    public function register():void
    {
        $this->app->bind(IVenuePaymentRepository::class, VenuePaymentRepository::class);
        $this->app->bind(IVenuePaymentService::class, VenuePaymentService::class);
    }

    public function boot(): void
    {
        //
    }
}
