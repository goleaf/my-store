<?php

namespace App\Livewire;

use App\Traits\FetchesUrls;
use Illuminate\View\View;
use Livewire\Component;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use App\Models\Collection;

class CollectionPage extends Component
{
    use FetchesUrls;
    use CanAddToCart;
    use CanManageWishlist;

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
