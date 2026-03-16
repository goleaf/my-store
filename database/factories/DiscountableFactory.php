<?php

namespace App\Database\Factories;

use App\Models\Discountable;
use App\Models\ProductVariant;

class DiscountableFactory extends BaseFactory
{
    protected $model = Discountable::class;

    public function definition(): array
    {
        return [
            'discountable_id' => ProductVariant::factory(),
            'discountable_type' => ProductVariant::morphName(),
        ];
    }
}
