<?php

namespace App\Store\Database\Factories;

use App\Store\Models\Discountable;
use App\Store\Models\ProductVariant;

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
