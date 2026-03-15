<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Store\Models\Country;
use App\Store\Models\TaxClass;
use App\Store\Models\TaxRate;
use App\Store\Models\TaxZone;
use App\Store\Models\TaxZoneCountry;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run(): void
    {
        $taxClass = TaxClass::getDefault() ?? TaxClass::first();
        $ukCountry = Country::firstWhere('iso3', 'GBR');

        if (! $taxClass || ! $ukCountry) {
            return;
        }

        $hasDefaultZone = TaxZone::whereDefault(true)->exists();

        $ukTaxZone = TaxZone::factory()->create([
            'name' => 'UK',
            'active' => true,
            'default' => ! $hasDefaultZone,
            'zone_type' => 'country',
        ]);

        TaxZoneCountry::factory()->create([
            'country_id' => $ukCountry->id,
            'tax_zone_id' => $ukTaxZone->id,
        ]);

        $ukRate = TaxRate::factory()->create([
            'name' => 'VAT',
            'tax_zone_id' => $ukTaxZone->id,
            'priority' => 1,
        ]);

        $ukRate->taxRateAmounts()->createMany([
            [
                'percentage' => 20,
                'tax_class_id' => $taxClass->id,
            ],
        ]);
    }
}
