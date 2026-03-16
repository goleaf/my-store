<?php

namespace App\Database\State;

use Illuminate\Support\Facades\Schema;
use App\Facades\DB;

class ConvertBackOrderPurchasability
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        DB::usingConnection(config('store.database.connection') ?: DB::getDefaultConnection(), function () {
            $prefix = config('store.database.table_prefix');
            if ($this->canRun() && $this->shouldRun()) {
                DB::table("{$prefix}product_variants")->where([
                    'purchasable' => 'backorder',
                ])->update([
                    'purchasable' => 'in_stock_or_on_backorder',
                ]);
            }
        });
    }

    protected function canRun(): bool
    {
        $prefix = config('store.database.table_prefix');

        return Schema::hasTable("{$prefix}product_variants");
    }

    protected function shouldRun(): bool
    {
        $prefix = config('store.database.table_prefix');

        return (bool) DB::table("{$prefix}product_variants")->where([
            'purchasable' => 'backorder',
        ])->count();
    }
}
