<?php

namespace App\Livewire;

use App\Models\Wishlist;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class WishlistPage extends Component
{
    use CanAddToCart;
    use CanManageWishlist;

    protected $listeners = [
        'wishlistUpdated' => '$refresh',
    ];

    public function getItemsProperty(): Collection
    {
        return Wishlist::query()
            ->where('customer_id', auth()->id())
            ->with([
                'product.defaultUrl',
                'product.variants.basePrices.currency',
                'product.brand.defaultUrl',
                'product.tags',
                'product.media',
                'variant',
            ])
            ->latest()
            ->get();
    }

    public function render(): View
    {
        return view('livewire.wishlist-page')
            ->layout('layouts.storefront');
    }
}
