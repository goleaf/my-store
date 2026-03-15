<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use App\Store\Models\Collection;
use App\Store\Models\Url;

class Home extends Component
{
    /**
     * Return the sale collection (with defaultUrl and description for Filament-backed fields).
     */
    public function getSaleCollectionProperty(): Collection | null
    {
        $collection = Url::whereElementType((new Collection)->getMorphClass())
            ->whereSlug('sale')
            ->first()?->element ?? null;
        if ($collection) {
            $collection->load(['defaultUrl']);
        }
        return $collection;
    }

    /**
     * Return all images in sale collection.
     */
    public function getSaleCollectionImagesProperty()
    {
        if (! $this->getSaleCollectionProperty()) {
            return null;
        }

        $collectionProducts = $this->getSaleCollectionProperty()
            ->products()->inRandomOrder()->limit(4)->get();

        $saleImages = $collectionProducts->map(function ($product) {
            return $product->thumbnail;
        });

        return $saleImages->chunk(2);
    }

    /**
     * Return a random collection (with products loaded for cards – all Filament-backed fields).
     */
    public function getRandomCollectionProperty(): ?Collection
    {
        $collections = Url::whereElementType((new Collection)->getMorphClass());

        if ($this->getSaleCollectionProperty()) {
            $collections = $collections->where('element_id', '!=', $this->getSaleCollectionProperty()?->id);
        }

        $collection = $collections->inRandomOrder()->first()?->element;
        if ($collection) {
            $collection->load([
                'products.variants.basePrices.currency',
                'products.defaultUrl',
                'products.brand',
                'products.tags',
            ]);
        }
        return $collection;
    }

    public function render(): View
    {
        return view('livewire.home');
    }
}
