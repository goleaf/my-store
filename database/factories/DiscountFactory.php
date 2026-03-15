<?php

namespace App\Store\Database\Factories;

use Illuminate\Support\Str;
use App\Store\DiscountTypes\AmountOff;
use App\Store\Models\Discount;

class DiscountFactory extends BaseFactory
{
    protected $model = Discount::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->name;

        return [
            'name' => $name,
            'handle' => Str::snake($name),
            'type' => AmountOff::class,
            'starts_at' => now(),
        ];
    }
}
