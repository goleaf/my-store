<?php

namespace App\Store\Jobs\Collections;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Store\Actions\Collections\SortProducts;
use App\Store\Facades\DB;
use App\Store\Models\Collection;
use App\Store\Models\Contracts\Collection as CollectionContract;

class UpdateProductPositions implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $tries = 1;

    /**
     * The target collection.
     */
    public CollectionContract $collection;

    /**
     * Create a new job instance.
     */
    public function __construct(CollectionContract $collection)
    {
        $this->collection = $collection;
    }

    public function handle()
    {
        if ($this->collection->sort == 'custom') {
            return;
        }

        DB::transaction(function () {
            $products = app(SortProducts::class)->execute($this->collection);
            $productSync = $products->values()->mapWithKeys(function ($product, $index) {
                return [
                    $product->id => [
                        'position' => $index + 1,
                    ],
                ];
            });
            $this->collection->products()->sync($productSync);
        });
    }
}
