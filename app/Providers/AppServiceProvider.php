<?php

namespace App\Providers;

use App\Contracts\Parsers\News;
use App\Services\Parsers\Rbk;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(News::class, function ($app){
            return new Rbk();
        });
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
