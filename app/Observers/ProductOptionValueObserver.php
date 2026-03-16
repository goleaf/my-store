<?php

namespace App\Observers;

use App\Models\Contracts\ProductOptionValue as ProductOptionValueContract;
use App\Models\ProductOptionValue;

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
