<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\ICourtSlotRepository;
use App\Repository\Impl\CourtSlotRepository;
use App\Services\ICourtSlotService;
use App\Services\Impl\CourtSlotService;

class CourtSlotProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ICourtSlotRepository::class, CourtSlotRepository::class);
        $this->app->bind(ICourtSlotService::class, CourtSlotService::class);
    }

    public function boot(): void
    {
        //
    }
}
