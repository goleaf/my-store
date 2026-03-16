<?php

namespace App\Shipping\DataTransferObjects;

use App\Models\Contracts\Country as CountryContract;

class PostcodeLookup
{
    /**
     * Initialise the postcode lookup class.
     */
    public function __construct(
        public CountryContract $country,
        public string $postcode
    ) {
        //
    }
}
