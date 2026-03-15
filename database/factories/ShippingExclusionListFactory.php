<?php

namespace App\Shipping\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Shipping\Models\ShippingExclusionList;

class ShippingExclusionListFactory extends Factory
{
    protected $model = ShippingExclusionList::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
