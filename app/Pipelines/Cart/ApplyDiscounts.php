<?php

namespace App\Pipelines\Cart;

use Closure;
use App\Facades\Discounts;
use App\Models\Contracts\Cart;

final class ApplyDiscounts
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(\App\Models\Contracts\Cart): mixed  $next
     */
    public function handle(Cart $cart, Closure $next): mixed
    {
        /** @var \App\Models\Cart $cart */
        $cart->discounts = collect([]);
        $cart->discountBreakdown = collect([]);

        Discounts::apply($cart);

        return $next($cart);
    }
}
