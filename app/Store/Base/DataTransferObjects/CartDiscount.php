<?php

namespace App\Store\Base\DataTransferObjects;

use App\Store\Models\Contracts\Cart;
use App\Store\Models\Contracts\CartLine;
use App\Store\Models\Contracts\Discount;

class CartDiscount
{
    public function __construct(
        public CartLine|Cart $model,
        public Discount $discount
    ) {
        //
    }
}
