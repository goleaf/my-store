<?php

namespace App\Livewire;

use App\Models\Product;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class SearchPage extends Component
{
    use CanAddToCart;
    use CanManageWishlist;
    use WithPagination;

    /**
     * {@inheritDoc}
     */
    protected $queryString = [
        'term',
    ];

    /**
     * The search term.
     */
    public ?string $term = null;

    /**
     * Return the search results (eager load all relations used by product card / Filament-backed fields).
     */
    public function getResultsProperty(): LengthAwarePaginator
    {
        return Product::search($this->term)
            ->query(fn ($builder) => $builder->with([
                'defaultUrl',
                'variants.basePrices.currency',
                'brand',
                'tags',
                'thumbnail',
            ]))
            ->paginate(50);
    }

    public function render(): View
    {
        return view('livewire.search-page');
    }
}
