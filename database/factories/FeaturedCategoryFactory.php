<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\FeaturedCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeaturedCategory>
 */
class FeaturedCategoryFactory extends Factory
{
    protected $model = FeaturedCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'collection_id' => Collection::factory(),
            'title' => $this->faker->words(2, true),
            'image' => null,
            'sort_order' => $this->faker->numberBetween(1, 50),
            'is_active' => true,
        ];
    }
}
