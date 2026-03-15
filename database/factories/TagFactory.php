<?php

namespace App\Store\Database\Factories;

use Illuminate\Support\Str;
use App\Store\Models\Tag;

class TagFactory extends BaseFactory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'value' => Str::upper($this->faker->word),
        ];
    }
}
