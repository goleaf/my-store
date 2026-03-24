<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use App\Traits\FetchesUrls;
use Illuminate\View\View;
use Livewire\Component;

class BrandPage extends Component
{
    use CanAddToCart;
    use CanManageWishlist;
    use FetchesUrls;

    public function mount($slug): void
    {
        $this->url = $this->fetchUrl(
            $slug,
            (new Brand)->getMorphClass(),
            [
                'element.products.defaultUrl',
                'element.products.variants.basePrices.currency',
                'element.products.brand',
                'element.products.thumbnail',
            ]
        );

        if (! $this->url) {
            abort(404);
        }
    }

    public function getBrandProperty(): Brand
    {
        return $this->url->element;
    }

    public function render(): View
    {
        return view('livewire.brand-page')
            ->layout('layouts.storefront');
    }
}
