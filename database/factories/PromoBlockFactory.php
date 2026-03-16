<?php

namespace Database\Factories;

use App\Models\PromoBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PromoBlock>
 */
class PromoBlockFactory extends Factory
{
    protected $model = PromoBlock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->sentence(6),
            'badge_text' => $this->faker->boolean(70) ? strtoupper($this->faker->bothify('SAVE ##%')) : null,
            'image' => null,
            'bg_color' => $this->faker->randomElement(['#fef3c7', '#dcfce7', '#dbeafe']),
            'position' => $this->faker->randomElement(['middle', 'daily_best_promo', 'footer']),
            'cta_text' => $this->faker->boolean(80) ? 'Shop now' : null,
            'cta_url' => '/shop',
            'sort_order' => $this->faker->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
