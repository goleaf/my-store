<?php

namespace App\Shipping\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\DataTypes\ShippingOption;
use App\Models\Contracts\Cart;

interface ShippingRate
{
    public function shippingZone(): BelongsTo;

    public function shippingMethod(): BelongsTo;

    /**
     * Return the shipping method driver.
     */
    public function getShippingOption(Cart $cart): ?ShippingOption;
}
