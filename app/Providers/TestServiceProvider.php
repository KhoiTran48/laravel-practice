<?php

namespace App\Providers;

use App\Http\Services\TestServiceOne;
use App\Http\Services\TestServiceTwo;
use Illuminate\Support\ServiceProvider;
use App\Http\Services\TestServiceInterface;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TestServiceInterface::class, function ($app) {
            return new TestServiceOne();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
