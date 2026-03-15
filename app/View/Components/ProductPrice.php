<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Store\Facades\Pricing;
use App\Store\Models\Price;
use App\Store\Models\ProductVariant;

class ProductPrice extends Component
{
    public ?Price $price = null;

    public ?ProductVariant $variant = null;

    public bool $showCompare = false;

    /**
     * Create a new component instance.
     */
    public function __construct($product = null, $variant = null, bool $showCompare = false)
    {
        $purchasable = $variant ?: $product?->variants?->first();
        if ($purchasable) {
            $this->variant = $purchasable instanceof ProductVariant ? $purchasable : null;
            $pricing = Pricing::for($purchasable)->get();
            $this->price = $pricing->matched;
        }
        $this->showCompare = $showCompare;
    }

    public function comparePrice(): ?Price
    {
        if (! $this->variant || ! $this->showCompare) {
            return null;
        }
        $base = $this->variant->basePrices->first();
        if (! $base || ! $base->compare_price) {
            return null;
        }
        return $base;
    }

    public function render(): View
    {
        return view('store.components.product-price');
    }
}
