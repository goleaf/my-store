<?php

namespace App\Base\DataTransferObjects;

use App\Models\Contracts\Cart;
use App\Models\Contracts\CartLine;
use App\Models\Contracts\Discount;

class CartDiscount
{
    public function __construct(
        public CartLine|Cart $model,
        public Discount $discount
    ) {
        //
    }
}
