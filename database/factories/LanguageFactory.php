<?php

namespace App\Store\Database\Factories;

use App\Store\Models\Language;

class LanguageFactory extends BaseFactory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->slug(),
            'name' => $this->faker->name(),
            'default' => true,
        ];
    }
}
