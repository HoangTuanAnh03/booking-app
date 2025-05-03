<?php

namespace App\Providers;

use App\Services\IMapService;
use App\Services\Impl\MapService;
use Illuminate\Support\ServiceProvider;

class MapProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IMapService::class, MapService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
