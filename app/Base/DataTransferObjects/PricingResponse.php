<?php

namespace App\Base\DataTransferObjects;

use Illuminate\Support\Collection;
use App\Models\Contracts\Price;

class PricingResponse
{
    public function __construct(
        public Price $matched,
        public Price $base,
        public Collection $priceBreaks,
        public Collection $customerGroupPrices,
    ) {
        //
    }
}
