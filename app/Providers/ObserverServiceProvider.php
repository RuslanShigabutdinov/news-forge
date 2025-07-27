<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

use App\Observers\NewsObserver;
use App\Models\News;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        News::observe(NewsObserver::class);
    }
}
