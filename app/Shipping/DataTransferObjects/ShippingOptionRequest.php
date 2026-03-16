<?php

namespace App\Shipping\DataTransferObjects;

use App\Models\Contracts\Cart as CartContract;
use App\Shipping\Models\ShippingRate;

class ShippingOptionRequest
{
    /**
     * Initialise the shipping option request class.
     */
    public function __construct(
        public ShippingRate $shippingRate,
        public CartContract $cart
    ) {
        //
    }
}
