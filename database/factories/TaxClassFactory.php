<?php

namespace App\Store\Database\Factories;

use App\Store\Models\TaxClass;

class TaxClassFactory extends BaseFactory
{
    protected $model = TaxClass::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'default' => false,
        ];
    }
}
