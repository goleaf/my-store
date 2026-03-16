<?php

namespace App\Observers;

use App\Models\Contracts\ProductOption as ProductOptionContract;
use App\Models\Contracts\ProductOptionValue as ProductOptionValueContract;
use App\Models\ProductOption;

class ProductOptionObserver
{
    /**
     * Handle the ProductOption "deleting" event.
     *
     * @return void
     */
    public function deleting(ProductOptionContract $productOption)
    {
        /** @var ProductOption $productOption */
        $productOption->products()->detach();
        /** @var ProductOptionValue $optionValue */
        $productOption->values()->each(
            fn (ProductOptionValueContract $optionValue) => $optionValue->delete()
        );
    }
}
