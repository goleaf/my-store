<?php

namespace App\Database\Factories;

use App\Models\Currency;
use App\Models\Price;

class PriceFactory extends BaseFactory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'price' => $this->faker->numberBetween(1, 2500),
            'compare_price' => $this->faker->numberBetween(1, 2500),
            'currency_id' => Currency::factory(),
        ];
    }
}
