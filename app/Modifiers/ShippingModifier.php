<?php

namespace App\Modifiers;

use App\Store\Models\Cart;

class ShippingModifier
{
    public function handle(Cart $cart, \Closure $next)
    {
        /**
         * Custom shipping option.
         * --------------------------------------------
         * If you do not wish to use the shipping add-on you can add
         * your own shipping options that will appear at checkout
         */

        if(config('shipping-tables.enabled') == false){
            \App\Store\Facades\ShippingManifest::addOption(
                new \App\Store\DataTypes\ShippingOption(
                    name: 'Basic Delivery',
                    description: 'Basic Delivery',
                    identifier: 'BASDEL',
                    price: new \App\Store\DataTypes\Price(500, $cart->currency, 1),
                    taxClass: \App\Store\Models\TaxClass::getDefault()
                )
            );
        }

        return $next($cart);
    }
}
