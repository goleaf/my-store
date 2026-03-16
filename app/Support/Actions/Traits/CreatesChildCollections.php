<?php

namespace App\Support\Actions\Traits;

use App\Facades\DB;
use App\Models\Attribute;
use App\Models\Collection;
use App\Models\Contracts;

trait CreatesChildCollections
{
    public function createChildCollection(Contracts\Collection $parent, array|string $name)
    {
        DB::beginTransaction();

        $attribute = Attribute::whereHandle('name')->whereAttributeType(
            Collection::morphName()
        )->first()->type;

        $parent->appendNode(Collection::create([
            'collection_group_id' => $parent->collection_group_id,
            'attribute_data' => [
                'name' => new $attribute($name),
            ],
        ]));

        DB::commit();
    }
}
