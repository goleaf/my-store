<?php

namespace App\Store\Database\Factories;

use App\Store\Models\Country;
use App\Store\Models\TaxZone;
use App\Store\Models\TaxZoneCountry;

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
