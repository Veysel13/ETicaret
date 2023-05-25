<?php

namespace App\Providers;

use App\Models\Cart\Cart;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/config/config.php' => config_path('shopping_cart.php'),
            ], 'config');
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('cart', function ($app) {
            $storageClass = config('shopping_cart.storage');
            $eventsClass = config('shopping_cart.events');
            $storage = $storageClass ? new $storageClass() : $app['session'];
            $events = $eventsClass ? new $eventsClass() : $app['events'];
            $instanceName = 'cart';

            // default session or cart identifier. This will be overridden when calling Cart::session($sessionKey)->add() etc..
            // like when adding a cart for a specific user name. Session Key can be string or maybe a unique identifier to bind a cart
            // to a specific user, this can also be a user ID
            if (request()->header('Authorization') === 'Bearer 43b9b66fb661097de7192237535dd653') {
                $session_key = request()->header('userId') . '_procat';
            } else if (request()->has('callCenterBasketKey')) {
                $session_key = request()->input('callCenterBasketKey') . '_callcenter';
            } else if (auth()->check()) {
                $session_key = auth()->id();
            } else {
                $session_key = \Str::random(12) . '_guest';
            }
            return new Cart(
                $storage,
                $events,
                $instanceName,
                $session_key,
                config('shopping_cart')
            );
        });

    }
}
