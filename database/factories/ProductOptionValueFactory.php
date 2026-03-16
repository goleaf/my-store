<?php

namespace App\Database\Factories;

use App\Models\ProductOptionValue;

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
