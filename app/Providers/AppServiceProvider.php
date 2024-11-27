<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;


ini_set('memory_limit', '2048M');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        // Use Bootstrap pagination
        // dd('hi');
        Paginator::useBootstrap();
        if (env('APP_ENV') === 'production' || env('APP_ENV') === 'stage') {
            URL::forceScheme('https');
        }else{
            URL::forceScheme('http');
        }
    }
}
