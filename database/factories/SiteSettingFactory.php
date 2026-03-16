<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $label = Str::title($this->faker->unique()->words(2, true));

        return [
            'key' => Str::slug($label, '_'),
            'value' => $this->faker->sentence(),
            'group' => $this->faker->randomElement(['general', 'homepage', 'checkout', 'social']),
            'type' => $this->faker->randomElement(['text', 'boolean', 'json', 'image']),
            'label' => $label,
            'description' => $this->faker->sentence(),
        ];
    }
}
