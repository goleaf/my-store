<?php

namespace App\Store\Database\Factories;

use App\Store\Models\CustomerGroup;
use App\Store\Models\TaxZone;
use App\Store\Models\TaxZoneCustomerGroup;

class TaxZoneCustomerGroupFactory extends BaseFactory
{
    protected $model = TaxZoneCustomerGroup::class;

    public function definition(): array
    {
        return [
            'customer_group_id' => CustomerGroup::factory(),
            'tax_zone_id' => TaxZone::factory(),
        ];
    }
}
