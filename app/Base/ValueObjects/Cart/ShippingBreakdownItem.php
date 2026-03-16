<?php

namespace App\Store\Base\ValueObjects\Cart;

use App\Store\DataTypes\Price;

class ShippingBreakdownItem
{
    public function __construct(
        public string $name,
        public string $identifier,
        public Price $price
    ) {
        //
    }
}
