<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company() . ' Market';

        return [
            'owner_id' => Customer::query()->orderBy('id')->value('id') ?? Customer::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'logo' => null,
            'banner' => null,
            'description' => $this->faker->paragraph(),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address_line_1' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => 'United States',
            'opening_hours' => [
                'mon' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                'tue' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                'wed' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                'thu' => ['open' => '08:00', 'close' => '20:00', 'closed' => false],
                'fri' => ['open' => '08:00', 'close' => '21:00', 'closed' => false],
                'sat' => ['open' => '09:00', 'close' => '21:00', 'closed' => false],
                'sun' => ['open' => '10:00', 'close' => '18:00', 'closed' => false],
            ],
            'commission_rate' => $this->faker->randomFloat(2, 5, 18),
            'rating_avg' => $this->faker->randomFloat(2, 4, 5),
            'total_reviews' => $this->faker->numberBetween(5, 120),
            'is_verified' => $this->faker->boolean(70),
            'is_active' => true,
            'meta_title' => $name,
            'meta_description' => $this->faker->sentence(12),
        ];
    }
}
