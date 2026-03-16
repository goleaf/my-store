<?php

namespace App\Store\Pipelines\Cart;

use Closure;
use App\Store\Base\ValueObjects\Cart\ShippingBreakdown;
use App\Store\Base\ValueObjects\Cart\ShippingBreakdownItem;
use App\Store\DataTypes\Price;
use App\Store\Facades\ShippingManifest;
use App\Store\Models\Cart;
use App\Store\Models\Contracts\Cart as CartContract;

final class ApplyShipping
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(CartContract): mixed  $next
     */
    public function handle(CartContract $cart, Closure $next): mixed
    {
        /** @var Cart $cart */
        $shippingSubTotal = 0;
        $shippingBreakdown = $cart->shippingBreakdown ?: new ShippingBreakdown;

        $shippingOption = $cart->shippingOptionOverride ?: ShippingManifest::getShippingOption($cart);

        if ($shippingOption) {
            if ($cart->shippingOptionOverride) {
                $shippingBreakdown->items = collect();
            }

            $shippingBreakdown->items->put(
                $shippingOption->getIdentifier(),
                new ShippingBreakdownItem(
                    name: $shippingOption->getName(),
                    identifier: $shippingOption->getIdentifier(),
                    price: $shippingOption->price,
                )
            );

            $shippingSubTotal = $shippingOption->price->value;
            $shippingTotal = $shippingSubTotal;

            if ($cart->shippingAddress && ! $cart->shippingBreakdown) {
                $cart->shippingAddress->shippingTotal = new Price($shippingTotal, $cart->currency, 1);
                $cart->shippingAddress->shippingSubTotal = new Price($shippingOption->price->value, $cart->currency, 1);
            }
        }

        $cart->shippingBreakdown = $shippingBreakdown;

        $cart->shippingSubTotal = new Price(
            $shippingBreakdown->items->sum('price.value'),
            $cart->currency,
            1
        );

        return $next($cart);
    }
}
