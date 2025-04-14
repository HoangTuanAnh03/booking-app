<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\ICourtRepository;
use App\Repository\Impl\CourtRepository;
use App\Services\ICourtService;
use App\Services\Impl\CourtService;

class CourtProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ICourtRepository::class, CourtRepository::class);
        $this->app->bind(ICourtService::class, CourtService::class);
    }

    public function boot(): void
    {
        //
    }
}
