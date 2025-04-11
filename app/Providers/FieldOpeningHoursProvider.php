<?php

namespace App\Providers;

use App\Repository\IFieldOpeningHoursRepository;
use App\Repository\ILocationServiceRepository;
use App\Repository\Impl\FieldOpeningHoursRepository;
use App\Repository\Impl\LocationServiceRepository;
use App\Services\IFieldOpeningHoursService;
use App\Services\ILocationServiceService;
use App\Services\Impl\FieldOpeningHoursService;
use App\Services\Impl\LocationServiceService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class FieldOpeningHoursProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IFieldOpeningHoursService::class, FieldOpeningHoursService::class);
        $this->app->bind(IFieldOpeningHoursRepository::class, FieldOpeningHoursRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
