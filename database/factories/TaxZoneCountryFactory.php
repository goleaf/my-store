<?php

namespace App\Database\Factories;

use App\Models\Country;
use App\Models\TaxZone;
use App\Models\TaxZoneCountry;

class TaxZoneCountryFactory extends BaseFactory
{
    protected $model = TaxZoneCountry::class;

    public function definition(): array
    {
        return [
            'tax_zone_id' => TaxZone::factory(),
            'country_id' => Country::factory(),
        ];
    }
}
