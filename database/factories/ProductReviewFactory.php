<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductReview>
 */
class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = $this->faker->numberBetween(3, 5);

        return [
            'product_id' => Product::query()->orderBy('id')->value('id') ?? Product::factory(),
            'customer_id' => Customer::query()->orderBy('id')->value('id') ?? Customer::factory(),
            'rating' => $rating,
            'rating_flavor' => $rating,
            'rating_value' => $this->faker->numberBetween(3, 5),
            'rating_scent' => $this->faker->numberBetween(3, 5),
            'title' => $this->faker->sentence(4),
            'body' => $this->faker->paragraph(),
            'images' => [],
            'helpful_count' => $this->faker->numberBetween(0, 10),
            'is_verified_purchase' => $this->faker->boolean(75),
            'is_approved' => true,
            'is_flagged' => false,
            'admin_reply' => null,
            'admin_replied_at' => null,
        ];
    }
}
