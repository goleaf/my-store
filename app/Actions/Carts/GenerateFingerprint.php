<?php

namespace App\Actions\Carts;

use App\Models\Cart;
use App\Models\CartLine;
use App\Models\Contracts\Cart as CartContract;
use App\Models\Contracts\CartLine as CartLineContract;

class GenerateFingerprint
{
    public function execute(CartContract $cart)
    {
        /** @var Cart $cart */
        $value = $cart->lines->reduce(function (?string $carry, CartLineContract $line) {
            /** @var CartLine $line */
            return $carry.
                $line->purchasable_type.
                $line->purchasable_id.
                $line->quantity.
                $line->subTotal;
        });

        $value .= $cart->user_id.$cart->currency_id.$cart->coupon_code;

        return sha1($value);
    }
}
