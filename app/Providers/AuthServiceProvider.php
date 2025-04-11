<?php

namespace App\Providers;

use App\Models\Venue;
use App\Models\VenueImage;
use App\Policies\VenueImagePolicy;
use App\Policies\VenuePolicy;
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
