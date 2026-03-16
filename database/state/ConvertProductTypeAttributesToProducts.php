<?php

namespace App\Database\State;

use Illuminate\Support\Facades\Schema;
use App\Facades\DB;
use App\Models\ProductType;

class ConvertProductTypeAttributesToProducts
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        $prefix = config('store.database.table_prefix');

        if (! $this->canRun()) {
            return;
        }

        DB::table("{$prefix}attributes")
            ->whereAttributeType(
                ProductType::morphName()
            )
            ->update([
                'attribute_type' => 'product',
            ]);

        DB::table("{$prefix}attribute_groups")
            ->whereAttributableType(
                ProductType::morphName()
            )
            ->update([
                'attributable_type' => 'product',
            ]);
    }

    protected function canRun()
    {
        $prefix = config('store.database.table_prefix');

        return Schema::hasTable("{$prefix}attributes") &&
            Schema::hasTable("{$prefix}attribute_groups");
    }
}
