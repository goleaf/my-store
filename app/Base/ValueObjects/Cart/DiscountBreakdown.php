<?php

namespace App\Base\ValueObjects\Cart;

use Illuminate\Support\Collection;
use App\DataTypes\Price;
use App\Models\Contracts\Discount;

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
