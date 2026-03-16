<?php

namespace App\Database\Factories;

use App\FieldTypes\Text;
use App\Models\Collection;
use App\Models\CollectionGroup;

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
