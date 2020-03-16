<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Pastel;
use App\Observers\OrderObserver;
use App\Observers\ClientObserver;
use App\Observers\PastelObserver;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Client::observe(ClientObserver::class);
        Pastel::observe(PastelObserver::class);
        Order::observe(OrderObserver::class);
    }
}
