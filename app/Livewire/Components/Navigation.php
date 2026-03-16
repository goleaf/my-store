<?php

namespace App\Livewire\Components;

use Illuminate\View\View;
use Livewire\Component;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Wishlist;

class Navigation extends Component
{
    protected $listeners = [
        'wishlistUpdated' => '$refresh',
    ];

    /**
     * The search term for the search input.
     *
     * @var string
     */
    public $term = null;

    /**
     * {@inheritDoc}
     */
    protected $queryString = [
        'term',
    ];

    /**
     * Return the collections in a tree.
     */
    public function getCollectionsProperty()
    {
        return Collection::with(['defaultUrl'])->get()->toTree();
    }

    /**
     * Return the brands.
     */
    public function getBrandsProperty()
    {
        return Brand::with(['defaultUrl'])->get();
    }

    public function getWishlistCountProperty(): int
    {
        if (! auth()->check()) {
            return 0;
        }

        return Wishlist::query()
            ->where('customer_id', auth()->id())
            ->count();
    }

    public function getCanAccessAdminProperty(): bool
    {
        return auth('staff')->check();
    }

    public function render(): View
    {
        return view('livewire.components.navigation');
    }
}
