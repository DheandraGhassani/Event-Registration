<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       //Fortify::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public const HOME = '/redirect';
}
