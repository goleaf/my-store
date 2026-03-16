<?php

namespace App\Actions\Collections;

use App\Models\Collection;
use App\Models\Contracts\Collection as CollectionContract;
use App\Models\Currency;

class SortProducts
{
    /**
     * Execute the action.
     *
     * @return void
     */
    public function execute(CollectionContract $collection)
    {
        /** @var Collection $collection */
        [$sort, $direction] = explode(':', $collection->sort);

        switch ($sort) {
            case 'min_price':
                $products = app(SortProductsByPrice::class)->execute(
                    $collection->products,
                    Currency::getDefault(),
                    $direction
                );
                break;
            case 'sku':
                $products = app(SortProductsBySku::class)->execute(
                    $collection->products,
                    $direction
                );
                break;
            default:
                $products = $collection->products;
                break;
        }

        return $products;
    }
}
