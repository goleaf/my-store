<?php

namespace App\Observers;

use App\Models\Contracts\ProductOptionValue;

class ProductOptionValueObserver
{
    /**
     * Handle the \App\Models\ProductOptionValue "deleting" event.
     *
     * @return void
     */
    public function deleting(ProductOptionValue $productOptionValue)
    {
        /** @var \App\Models\ProductOptionValue $productOptionValue */
        $productOptionValue->variants()->detach();
    }
}
