<?php

namespace App\Store\Database\Factories;

use App\Store\FieldTypes\Text;
use App\Store\Models\Collection;
use App\Store\Models\CollectionGroup;

class CollectionFactory extends BaseFactory
{
    protected $model = Collection::class;

    public function definition(): array
    {
        return [
            'collection_group_id' => CollectionGroup::factory(),
            'attribute_data' => collect([
                'name' => new Text($this->faker->name),
            ]),
        ];
    }
}
