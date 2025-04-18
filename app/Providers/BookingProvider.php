<?php

namespace App\Providers;

use App\Repository\IBookingCourtRepository;
use App\Repository\IBookingRepository;
use App\Repository\Impl\BookingCourtRepository;
use App\Repository\Impl\BookingRepository;
use App\Services\IBookingCourtService;
use App\Services\IBookingService;
use App\Services\Impl\BookingCourtService;
use App\Services\Impl\BookingService;
use Illuminate\Support\ServiceProvider;

class BookingProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(IBookingRepository::class, BookingRepository::class);
        $this->app->bind(IBookingService::class, BookingService::class);
    }

    public function boot(): void
    {
        //
    }
}
