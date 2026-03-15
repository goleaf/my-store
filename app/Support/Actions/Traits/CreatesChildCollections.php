<?php

namespace App\Support\Actions\Traits;

use App\Store\Facades\DB;
use App\Store\Models\Attribute;
use App\Store\Models\Collection;
use App\Store\Models\Contracts\Collection as CollectionContract;

trait CreatesChildCollections
{
    public function createChildCollection(CollectionContract $parent, array|string $name)
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
