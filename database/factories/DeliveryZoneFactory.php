<?php

namespace Database\Factories;

use App\Models\Store\Models\DeliveryZone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeliveryZone>
 */
class DeliveryZoneFactory extends Factory
{
    protected $model = DeliveryZone::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city(),
            'min_order' => $this->faker->randomFloat(2, 0, 60),
            'delivery_fee' => $this->faker->randomFloat(2, 0, 15),
            'is_active' => true,
        ];
    }
}
