<?php

namespace App\Actions\Carts;

use App\Models\Contracts\Cart;
use App\Models\Contracts\CartLine;

class GenerateFingerprint
{
    public function execute(Cart $cart)
    {
        /** @var \App\Models\Cart $cart */
        $value = $cart->lines->reduce(function (?string $carry, CartLine $line) {
            /** @var \App\Models\CartLine $line */
            return $carry.
                $line->purchasable_type.
                $line->purchasable_id.
                $line->quantity.
                $line->subTotal;
        });

        $value .= $cart->customer_id.$cart->currency_id.$cart->coupon_code;

        return sha1($value);
    }
}
