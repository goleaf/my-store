<?php

namespace App\Shipping\Interfaces;

use App\DataTypes\ShippingOption;
use App\Shipping\DataTransferObjects\ShippingOptionRequest;
use App\Shipping\Models\ShippingRate;

interface ShippingRateInterface
{
    /**
     * Return the name of the shipping method.
     */
    public function name(): string;

    /**
     * Return the description of the shipping method.
     */
    public function description(): string;

    /**
     * Set the context for the driver.
     */
    public function on(ShippingRate $shippingRate): self;

    /**
     * Return the shipping option price.
     */
    public function resolve(ShippingOptionRequest $shippingOptionRequest): ?ShippingOption;
}
