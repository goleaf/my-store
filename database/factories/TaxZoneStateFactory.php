<?php

namespace App\Database\Factories;

use App\Models\State;
use App\Models\TaxZone;
use App\Models\TaxZoneState;

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
