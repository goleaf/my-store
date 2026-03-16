<?php

namespace App\Store\Base\ValueObjects\Cart;

use App\Store\Models\Contracts\CartLine;

class DiscountBreakdownLine
{
    public function __construct(
        public CartLine $line,
        public int $quantity,
    ) {
        //
    }
}
