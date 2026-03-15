<?php

namespace App\Store\Database\Factories;

use App\Store\Models\TaxClass;
use App\Store\Models\TaxRate;
use App\Store\Models\TaxRateAmount;

class TaxRateAmountFactory extends BaseFactory
{
    protected $model = TaxRateAmount::class;

    public function definition(): array
    {
        return [
            'tax_rate_id' => TaxRate::factory(),
            'tax_class_id' => TaxClass::factory(),
            'percentage' => 20,
        ];
    }
}
