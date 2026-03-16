<?php

namespace App\Database\Factories;

use App\Models\TaxClass;
use App\Models\TaxRate;
use App\Models\TaxRateAmount;

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
