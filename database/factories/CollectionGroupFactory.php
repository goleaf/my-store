<?php

namespace App\Store\Database\Factories;

use Illuminate\Support\Str;
use App\Store\Models\CollectionGroup;

class CollectionGroupFactory extends BaseFactory
{
    protected $model = CollectionGroup::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'name' => $name,
            'handle' => Str::slug($name),
        ];
    }
}
