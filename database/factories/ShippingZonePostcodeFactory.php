<?php

namespace App\Shipping\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Shipping\Models\ShippingZonePostcode;

class ShippingZonePostcodeFactory extends Factory
{
    protected $model = ShippingZonePostcode::class;

    public function definition(): array
    {
        return [
            'postcode' => $this->faker->postcode,
        ];
    }
}
