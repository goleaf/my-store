<?php

namespace App\Store\Pipelines\Cart;

use Closure;
use App\Store\Facades\Discounts;
use App\Store\Models\Cart;
use App\Store\Models\Contracts\Cart as CartContract;

final class ApplyDiscounts
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(CartContract): mixed  $next
     */
    public function handle(CartContract $cart, Closure $next): mixed
    {
        /** @var Cart $cart */
        $cart->discounts = collect([]);
        $cart->discountBreakdown = collect([]);

        Discounts::apply($cart);

        return $next($cart);
    }
}
