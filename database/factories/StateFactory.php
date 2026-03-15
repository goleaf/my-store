<?php

namespace App\Store\Database\Factories;

use Illuminate\Support\Str;
use App\Store\Models\State;

class StateFactory extends BaseFactory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->country,
            'code' => Str::random(),
        ];
    }
}
