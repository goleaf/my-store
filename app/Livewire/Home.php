<?php

namespace App\Livewire;

use App\Base\Enums\HomeBannerType;
use App\Models\Collection;
use App\Models\FeaturedCategory;
use App\Models\HomeBanner;
use App\Models\HomeHero;
use App\Models\HomeSection;
use App\Models\Url;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\View;
use Livewire\Component;

class Home extends Component
{
    use CanAddToCart;
    use CanManageWishlist;

    /**
     * Get active home heroes.
     */
    public function getHeroesProperty(): EloquentCollection
    {
        return HomeHero::query()
            ->select([
                'id',
                'title',
                'subtitle',
                'description',
                'link',
                'button_text',
                'image',
                'sort_order',
                'is_active',
            ])
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get active featured categories.
     */
    public function getFeaturedCategoriesProperty(): EloquentCollection
    {
        return FeaturedCategory::query()
            ->select([
                'id',
                'collection_id',
                'title',
                'image',
                'sort_order',
                'is_active',
            ])
            ->with([
                'collection' => fn ($query) => $query
                    ->select([
                        'id',
                        'attribute_data',
                    ])
                    ->with([
                        'defaultUrl' => fn ($relationQuery) => $relationQuery->select([
                            'id',
                            'element_id',
                            'element_type',
                            'slug',
                            'default',
                        ]),
                        'thumbnail',
                    ]),
            ])
            ->whereHas('collection', fn (Builder $query): Builder => $query->whereHas('defaultUrl'))
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get active home sections with their collections and products.
     */
    public function getSectionsProperty(): EloquentCollection
    {
        return HomeSection::query()
            ->select([
                'id',
                'title',
                'subtitle',
                'type',
                'collection_id',
                'sort_order',
                'is_active',
            ])
            ->with([
                'collection.defaultUrl',
                'collection.products.variants.basePrices.currency',
                'collection.products.variants.prices.currency',
                'collection.products.defaultUrl',
                'collection.products.brand',
                'collection.products.thumbnail',
                'collection.products.tags',
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get active home banners.
     */
    public function getBannersProperty(): EloquentCollection
    {
        return HomeBanner::query()
            ->select([
                'id',
                'title',
                'subtitle',
                'link',
                'image',
                'type',
                'sort_order',
                'is_active',
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getTopBannersProperty(): EloquentCollection
    {
        return $this->banners
            ->filter(fn (HomeBanner $banner): bool => $banner->type === HomeBannerType::Top)
            ->values();
    }

    public function getMiddleBannersProperty(): EloquentCollection
    {
        return $this->banners
            ->filter(fn (HomeBanner $banner): bool => $banner->type === HomeBannerType::Middle)
            ->values();
    }

    public function getBottomBannersProperty(): EloquentCollection
    {
        return $this->banners
            ->filter(fn (HomeBanner $banner): bool => $banner->type === HomeBannerType::Bottom)
            ->values();
    }

    /**
     * Return the sale collection (with defaultUrl and description for Filament-backed fields).
     */
    public function getSaleCollectionProperty(): ?Collection
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
