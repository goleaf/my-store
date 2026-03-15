<?php

namespace App\Store\Database\Factories;

use App\Store\Models\Brand;

class BrandFactory extends BaseFactory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
