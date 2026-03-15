<?php

namespace App\Store\Database\Factories;

use App\Store\Models\State;
use App\Store\Models\TaxZone;
use App\Store\Models\TaxZoneState;

class TaxZoneStateFactory extends BaseFactory
{
    protected $model = TaxZoneState::class;

    public function definition(): array
    {
        return [
            'tax_zone_id' => TaxZone::factory(),
            'state_id' => State::factory(),
        ];
    }
}
