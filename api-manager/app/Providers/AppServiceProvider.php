<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ChartRepository; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\ChartRepository::class,
            function ($app) {
                return new ChartRepository();
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
