<?php

namespace App\Observers;

use App\Models\Contracts\ProductVariant;

class ProductVariantObserver
{
    /**
     * Handle the \App\Models\ProductVariant "deleted" event.
     *
     * @return void
     */
    public function deleting(ProductVariant $productVariant)
    {
        if ($productVariant->isForceDeleting()) {
            /** @var \App\Models\ProductVariant $productVariant */
            $productVariant->prices()->delete();
            $productVariant->values()->detach();
            $productVariant->images()->detach();
        }
    }
}
