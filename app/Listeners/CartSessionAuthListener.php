<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Facades\CartSession;
use App\Models\Cart;

class CartSessionAuthListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the login event.
     *
     * @return void
     */
    public function login(Login $event)
    {
        if (! is_store_user($event->user)) {
            return;
        }

        $currentCart = CartSession::current();

        if ($currentCart && ! $currentCart->customer_id) {
            CartSession::associate(
                $currentCart,
                $event->user,
                config('store.cart.auth_policy')
            );
        }

        if (! $currentCart) {
            $customerCart = Cart::query()
                ->where('customer_id', $event->user->getKey())
                ->active()
                ->first();

            if ($customerCart) {
                CartSession::use($customerCart);
            }
        }
    }

    /**
     * Handle the logout event.
     *
     * @return void
     */
    public function logout(Logout $event)
    {
        if (is_null($event->user) || ! is_store_user($event->user)) {
            return;
        }

        CartSession::forget();
    }
}
