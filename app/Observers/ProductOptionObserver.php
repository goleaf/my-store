<?php

namespace App\Observers;

use App\Models\Contracts\ProductOptionValue;
use App\Models\Contracts\ProductOption;

class ProductOptionObserver
{
    /**
     * Handle the \App\Models\ProductOption "deleting" event.
     *
     * @return void
     */
    public function deleting(ProductOption $productOption)
    {
        /** @var \App\Models\ProductOption $productOption */
        $productOption->products()->detach();
        /** @var ProductOptionValue $optionValue */
        $productOption->values()->each(
            fn (ProductOptionValue $optionValue) => $optionValue->delete()
        );
    }
}
