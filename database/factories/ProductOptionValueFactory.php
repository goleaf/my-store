<?php

namespace App\Store\Database\Factories;

use App\Store\Models\ProductOptionValue;

class ProductOptionValueFactory extends BaseFactory
{
    protected $model = ProductOptionValue::class;

    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->name,
            ],
        ];
    }
}
