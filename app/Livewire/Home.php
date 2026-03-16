<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use App\Store\Models\Collection;
use App\Store\Models\Url;

use App\Store\Models\HomeHero;
use App\Store\Models\FeaturedCategory;
use App\Store\Models\HomeSection;
use App\Store\Models\HomeBanner;

class Home extends Component
{
    /**
     * Get active home heroes.
     */
    public function getHeroesProperty()
    {
        return HomeHero::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get active featured categories.
     */
    public function getFeaturedCategoriesProperty()
    {
        return FeaturedCategory::with(['collection.defaultUrl', 'collection.thumbnail'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get active home sections with their collections and products.
     */
    public function getSectionsProperty()
    {
        return HomeSection::with([
            'collection.products.variants.basePrices.currency',
            'collection.products.defaultUrl',
            'collection.products.brand',
        ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get active home banners.
     */
    public function getBannersProperty()
    {
        return HomeBanner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

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

    /**
     * Return all collections.
     */
    public function getCollectionsProperty()
    {
        return Collection::with(['defaultUrl', 'thumbnail'])->whereHas('defaultUrl')->get();
    }

    public function render(): View
    {
        return view('livewire.home');
    }
}
