<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wishlist>
 */
class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::query()->orderBy('id')->value('id') ?? Customer::factory(),
            'product_id' => Product::query()->orderBy('id')->value('id') ?? Product::factory(),
            'variant_id' => null,
        ];
    }
}
