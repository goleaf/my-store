<?php

namespace App\Store\Database\State;

use Illuminate\Support\Facades\Schema;
use App\Store\Facades\DB;
use App\Store\Models\ProductOption;

class PopulateProductOptionLabelWithName
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        if (! $this->canRun() || ! $this->shouldRun()) {
            return;
        }

        DB::transaction(function () {
            ProductOption::whereNull('label')
                ->update([
                    'label' => DB::raw('name'),
                ]);
        });
    }

    protected function canRun()
    {
        $prefix = config('store.database.table_prefix');

        return Schema::hasTable("{$prefix}product_options");
    }

    protected function shouldRun()
    {
        return ProductOption::whereNull('label')->count() > 0;
    }
}
