<?php

namespace Database\Factories;

use App\Base\Enums\DeliverySlotDayType;
use App\Models\DeliverySlot;
use App\Models\Store\Models\DeliveryZone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeliverySlot>
 */
class DeliverySlotFactory extends Factory
{
    protected $model = DeliverySlot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dayType = $this->faker->randomElement([
            DeliverySlotDayType::Recurring,
            DeliverySlotDayType::Specific,
        ]);

        return [
            'zone_id' => DeliveryZone::query()->orderBy('id')->value('id') ?? DeliveryZone::factory(),
            'day_type' => $dayType,
            'specific_date' => $dayType === DeliverySlotDayType::Specific
                ? now()->addDays($this->faker->numberBetween(1, 10))->toDateString()
                : null,
            'day_of_week' => $dayType === DeliverySlotDayType::Recurring
                ? $this->faker->numberBetween(0, 6)
                : null,
            'label' => $this->faker->randomElement([
                'Morning delivery',
                'Afternoon delivery',
                'Evening delivery',
            ]),
            'start_time' => $this->faker->randomElement(['09:00:00', '13:00:00', '18:00:00']),
            'end_time' => $this->faker->randomElement(['11:00:00', '15:00:00', '20:00:00']),
            'cutoff_hours' => $this->faker->numberBetween(0, 6),
            'fee' => $this->faker->randomFloat(2, 0, 8),
            'capacity' => $this->faker->numberBetween(10, 60),
            'booked_count' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
