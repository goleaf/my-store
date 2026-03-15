<?php

namespace App\Store\Observers;

use App\Store\Models\Contracts\ProductOptionValue as ProductOptionValueContract;
use App\Store\Models\ProductOptionValue;

class ProductOptionValueObserver
{
    /**
     * Handle the ProductOptionValue "deleting" event.
     *
     * @return void
     */
    public function deleting(ProductOptionValueContract $productOptionValue)
    {
        /** @var ProductOptionValue $productOptionValue */
        $productOptionValue->variants()->detach();
    }
}
