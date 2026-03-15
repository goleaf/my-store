<?php

namespace App\Store\Database\Factories;

use Illuminate\Support\Str;
use App\Store\Models\CustomerGroup;

class CustomerGroupFactory extends BaseFactory
{
    protected $model = CustomerGroup::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'name' => $name,
            'handle' => Str::slug($name),
        ];
    }
}
