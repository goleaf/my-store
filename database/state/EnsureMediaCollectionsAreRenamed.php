<?php

namespace App\Database\State;

use Illuminate\Support\Facades\Schema;
use App\Facades\DB;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\Product;

class EnsureMediaCollectionsAreRenamed
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        if (! $this->shouldRun()) {
            return;
        }

        $this->getOutdatedMediaQuery()->update(['collection_name' => config('store.media.collection')]);
    }

    protected function shouldRun()
    {
        return Schema::hasTable('media') && $this->getOutdatedMediaQuery()->count();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getOutdatedMediaQuery()
    {
        return DB::table(app(config('media-library.media_model'))->getTable())
            ->whereIn('model_type', [Product::morphName(), Collection::morphName(), Brand::morphName()])
            ->where('collection_name', 'products');
    }
}
