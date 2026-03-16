<?php

namespace App\Modifiers;

use App\Models\Cart;

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
            \App\Facades\ShippingManifest::addOption(
                new \App\DataTypes\ShippingOption(
                    name: 'Basic Delivery',
                    description: 'Basic Delivery',
                    identifier: 'BASDEL',
                    price: new \App\DataTypes\Price(500, $cart->currency, 1),
                    taxClass: \App\Models\TaxClass::getDefault()
                )
            );
        }

        return $next($cart);
    }
}
