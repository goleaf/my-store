<?php

namespace App\Base\ValueObjects\Cart;

use App\Models\Contracts\CartLine;

class DiscountBreakdownLine
{
    public function __construct(
        public CartLine $line,
        public int $quantity,
    ) {
        //
    }
}
