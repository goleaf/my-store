<?php

namespace Database\Factories;

use App\Models\Store\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => $this->faker->sentence(10),
            'is_active' => true,
            'bg_color' => $this->faker->randomElement(['#16a34a', '#ea580c', '#0f766e']),
            'text_color' => '#ffffff',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addWeek(),
        ];
    }
}
