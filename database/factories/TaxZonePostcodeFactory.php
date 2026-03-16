<?php

namespace App\Database\Factories;

use App\Models\Country;
use App\Models\TaxZone;
use App\Models\TaxZonePostcode;

class TaxZonePostcodeFactory extends BaseFactory
{
    protected $model = TaxZonePostcode::class;

    public function definition(): array
    {
        return [
            'tax_zone_id' => TaxZone::factory(),
            'country_id' => Country::factory(),
            'postcode' => $this->faker->postcode,
        ];
    }
}
