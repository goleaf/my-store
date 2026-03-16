<?php

namespace Database\Factories;

use App\Base\Enums\HomeBannerType;
use App\Models\HomeBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomeBanner>
 */
class HomeBannerFactory extends Factory
{
    protected $model = HomeBanner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->words(2, true),
            'link' => '/shop',
            'image' => null,
            'type' => HomeBannerType::Middle,
            'sort_order' => $this->faker->numberBetween(1, 50),
            'is_active' => true,
        ];
    }

    public function top(): static
    {
        return $this->state(fn (): array => [
            'type' => HomeBannerType::Top,
        ]);
    }

    public function middle(): static
    {
        return $this->state(fn (): array => [
            'type' => HomeBannerType::Middle,
        ]);
    }

    public function bottom(): static
    {
        return $this->state(fn (): array => [
            'type' => HomeBannerType::Bottom,
        ]);
    }
}
