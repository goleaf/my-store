<?php

namespace App\Livewire;

use App\Models\Collection;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use App\Traits\FetchesUrls;
use Illuminate\View\View;
use Livewire\Component;

class CollectionPage extends Component
{
    use CanAddToCart;
    use CanManageWishlist;
    use FetchesUrls;

    public function mount(string $slug): void
    {
        $this->url = $this->fetchUrl(
            $slug,
            (new Collection)->getMorphClass(),
            [
                'element.thumbnail',
                'element.products.variants.basePrices.currency',
                'element.products.defaultUrl',
                'element.products.brand',
                'element.products.tags',
                'element.products.thumbnail',
                'element.children.defaultUrl',
                'element.group',
            ]
        );

        if (! $this->url) {
            abort(404);
        }
    }

    /**
     * Computed property to return the collection.
     */
    public function getCollectionProperty(): mixed
    {
        return $this->url->element;
    }

    public function render(): View
    {
        return view('livewire.collection-page');
    }
}
