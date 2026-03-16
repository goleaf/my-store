<?php

namespace App\Shipping;

use App\Facades\ShippingManifest;
use App\Models\Contracts\Cart;
use App\Shipping\DataTransferObjects\ShippingOptionLookup;
use App\Shipping\Facades\Shipping;
use Closure;

class ShippingModifier
{
    public function handle(Cart $cart, Closure $next)
    {
        $shippingRates = Shipping::shippingRates($cart)->get();

        $options = Shipping::shippingOptions($cart)->get(
            new ShippingOptionLookup(
                shippingRates: $shippingRates
            )
        );

        foreach ($options as $option) {
            ShippingManifest::addOption($option->option);
        }

        return $next($cart);
    }
}
