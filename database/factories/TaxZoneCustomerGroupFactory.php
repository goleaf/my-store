<?php

namespace App\Database\Factories;

use App\Models\CustomerGroup;
use App\Models\TaxZone;
use App\Models\TaxZoneCustomerGroup;

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
