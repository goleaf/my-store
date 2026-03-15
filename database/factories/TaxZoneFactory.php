<?php

namespace App\Store\Database\Factories;

use App\Store\Models\TaxZone;

class TaxZoneFactory extends BaseFactory
{
    protected $model = TaxZone::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'zone_type' => $this->faker->randomElement(['country', 'postcode', 'state']),
            'price_display' => $this->faker->randomElement(['tax_inclusive', 'tax_exclusive']),
            'active' => true,
            'default' => true,
        ];
    }
}
