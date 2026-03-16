<?php

namespace App\Database\Factories;

use App\Models\TaxClass;

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
