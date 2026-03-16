<?php

namespace App\Store\Actions\Collections;

use App\Store\Models\Collection;
use App\Store\Models\Contracts\Collection as CollectionContract;
use App\Store\Models\Currency;

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
