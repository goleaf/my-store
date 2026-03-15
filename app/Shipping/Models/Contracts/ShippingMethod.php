<?php

namespace App\Shipping\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Shipping\Interfaces\ShippingRateInterface;

interface ShippingMethod
{
    public function shippingRates(): HasMany;

    public function driver(): ShippingRateInterface;
}
