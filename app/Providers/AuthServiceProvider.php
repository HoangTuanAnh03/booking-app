<?php

namespace App\Providers;

use App\Models\Venue;
use App\Models\VenueImage;
use App\Models\Court;
use App\Policies\VenueImagePolicy;
use App\Policies\VenuePolicy;
use App\Policies\CourtPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        Venue::class => VenuePolicy::class,
        VenueImage::class => VenueImagePolicy::class,
        Court::class => CourtPolicy::class,
    ];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();
    }
}
