<?php

namespace App\Shipping\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Shipping\Models\ShippingRate;

class ShippingRateFactory extends Factory
{
    protected $model = ShippingRate::class;

    public function definition(): array
    {
        return [];
    }
}
