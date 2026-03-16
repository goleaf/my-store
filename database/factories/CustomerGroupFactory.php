<?php

namespace App\Database\Factories;

use Illuminate\Support\Str;
use App\Models\CustomerGroup;

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
