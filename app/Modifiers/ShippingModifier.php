<?php

namespace App\Modifiers;

use App\Models\Cart;
use App\DataTypes\Price;
use App\DataTypes\ShippingOption;
use App\Facades\ShippingManifest;
use App\Models\TaxClass;
use Closure;

class ShippingModifier
{
    public function handle(Cart $cart, Closure $next)
    {
        /**
         * Custom shipping option.
         * --------------------------------------------
         * If you do not wish to use the shipping add-on you can add
         * your own shipping options that will appear at checkout
         */

        if(config('shipping-tables.enabled') == false){
            ShippingManifest::addOption(
                new ShippingOption(
                    name: 'Basic Delivery',
                    description: 'Basic Delivery',
                    identifier: 'BASDEL',
                    price: new Price(500, $cart->currency, 1),
                    taxClass: TaxClass::getDefault()
                )
            );
        }

        return $next($cart);
    }
}
