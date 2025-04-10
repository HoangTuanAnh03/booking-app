<?php

namespace App\Providers;

use App\Repository\Impl\ReviewRepository;
use App\Repository\IReviewRepository;
use App\Services\Impl\ReviewService;
use App\Services\IReviewService;
use Illuminate\Support\ServiceProvider;

class ReviewProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(IReviewRepository::class, ReviewRepository::class);
        $this->app->bind(IReviewService::class, ReviewService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
