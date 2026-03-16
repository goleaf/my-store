<?php

namespace App\Store\Observers;

use App\Store\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Store\Models\ProductVariant;

class ProductVariantObserver
{
    /**
     * Handle the ProductVariant "deleted" event.
     *
     * @return void
     */
    public function deleting(ProductVariantContract $productVariant)
    {
        if ($productVariant->isForceDeleting()) {
            /** @var ProductVariant $productVariant */
            $productVariant->prices()->delete();
            $productVariant->values()->detach();
            $productVariant->images()->detach();
        }
    }
}
