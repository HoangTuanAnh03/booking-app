<?php

namespace App\Providers;

use App\Repository\IFieldRepository;
use App\Repository\Impl\FieldRepository;
use App\Services\IFieldService;
use App\Services\Impl\FieldService;
use Illuminate\Support\ServiceProvider;
use function Psy\bin;

class FieldProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(IFieldRepository::class, FieldRepository::class);
        $this->app->bind(IFieldService::class, FieldService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
