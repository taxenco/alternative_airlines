<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AmadeusService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is used to bind services into the service container.
     * You can use this method to register any services or bindings that your application needs.
     * In this example, the AmadeusService is being registered as a singleton.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AmadeusService::class, function ($app) {
            return new AmadeusService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * This method is used to perform any actions required to initialize or bootstrap
     * your application services. This is typically used to set up configurations,
     * event listeners, or other tasks that need to be performed once the application
     * has been started.
     *
     * @return void
     */
    public function boot(): void
    {
        // You can perform actions required to bootstrap your application here.
    }
}
