<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\IBookingCourtRepository;
use App\Repository\Impl\BookingCourtRepository;
use App\Services\IBookingCourtService;
use App\Services\Impl\BookingCourtService;

class BookingCourtProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IBookingCourtRepository::class, BookingCourtRepository::class);
        $this->app->bind(IBookingCourtService::class, BookingCourtService::class);
    }

    public function boot(): void
    {
        //
    }
}
