<?php

namespace App\Store\Base;

use Closure;
use App\Store\Models\Contracts\Cart;
use App\Store\Models\Contracts\Order;

abstract class OrderModifier
{
    public function creating(Cart $cart, Closure $next): Cart
    {
        return $next($cart);
    }

    public function created(Order $order, Closure $next): Order
    {
        return $next($order);
    }
}
