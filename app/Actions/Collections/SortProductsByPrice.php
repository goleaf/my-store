<?php

namespace App\Actions\Collections;

use Illuminate\Support\Collection;
use App\Models\Currency;
use App\Models\Contracts;
use App\Models\Contracts\Product;

class SortProductsByPrice
{
    /**
     * Execute the action.
     */
    public function execute(Collection $products, Currency $currency, $direction = 'asc')
    {
        /** @var Collection $products */
        // Load up our products and prices.
        $products = $products->load('variants.basePrices');

        return $products->sort(function ($current, $next) use ($currency, $direction) {
            $currentPrice = $this->getMinPrice($current, $currency);
            $nextPrice = $this->getMinPrice($next, $currency);

            return $direction == 'asc' ? ($currentPrice > $nextPrice) : ($currentPrice < $nextPrice);
        });
    }

    protected function getMinPrice(Product $product, Contracts\Currency $currency)
    {
        /** @var \App\Models\Product $product */
        /** @var Currency $currency */
        return $product->variants->map(function ($variant) use ($currency) {
            // Get the prices for the currency
            return $variant->basePrices->filter(function ($price) use ($currency) {
                return $price->currency_id == $currency->id;
            })->min('price');
        })->min();
    }
}
