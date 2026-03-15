<?php

namespace App\Store\Base\ValueObjects\Cart;

use Illuminate\Support\Collection;
use App\Store\DataTypes\Price;
use App\Store\Models\Contracts\Discount;

class DiscountBreakdown
{
    public function __construct(
        public Price $price,
        public Collection $lines,
        public Discount $discount,
    ) {
        //
    }
}
