<?php

namespace App\Providers;

use App\Repository\IFieldPriceRepository;
use App\Repository\Impl\FieldPriceRepository;
use App\Services\IFieldPriceService;
use App\Services\Impl\FieldPriceService;
use Illuminate\Support\ServiceProvider;

class FieldPriceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IFieldPriceRepository::class, FieldPriceRepository::class);
        $this->app->bind(IFieldPriceService::class, FieldPriceService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
