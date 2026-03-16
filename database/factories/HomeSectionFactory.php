<?php

namespace Database\Factories;

use App\Base\Enums\HomeSectionType;
use App\Models\Collection;
use App\Models\HomeSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomeSection>
 */
class HomeSectionFactory extends Factory
{
    protected $model = HomeSection::class;

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
            'type' => HomeSectionType::ProductGrid,
            'collection_id' => Collection::factory(),
            'sort_order' => $this->faker->numberBetween(1, 50),
            'is_active' => true,
        ];
    }

    public function featuredItems(): static
    {
        return $this->state(fn (): array => [
            'type' => HomeSectionType::FeaturedItems,
        ]);
    }

    public function sidebarGrid(): static
    {
        return $this->state(fn (): array => [
            'type' => HomeSectionType::SidebarGrid,
        ]);
    }
}
