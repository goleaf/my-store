<?php

namespace App\Shipping\DataTransferObjects;

use App\Models\Contracts\Country;

class PostcodeLookup
{
    /**
     * Initialise the postcode lookup class.
     */
    public function __construct(
        public Country $country,
        public string $postcode
    ) {
        //
    }
}
