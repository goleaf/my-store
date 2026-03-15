<?php

namespace App\Store\Database\Factories;

use App\Store\Models\ProductType;

class ProductTypeFactory extends BaseFactory
{
    protected $model = ProductType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
