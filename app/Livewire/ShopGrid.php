<?php

namespace App\Livewire;

use App\Base\Enums\ProductStatus;
use App\Models;
use App\Models\Brand;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ShopGrid extends Component
{
    use CanAddToCart;
    use CanManageWishlist;
    use WithPagination;

    private const DEFAULT_PER_PAGE = 10;

    private const PER_PAGE_OPTIONS = [10, 20, 30];

    private const DEFAULT_SORT = 'featured';

    private const SORT_OPTIONS = ['featured', 'price_asc', 'price_desc', 'date', 'rating'];

    #[Url]
    public array $categories = [];

    #[Url]
    public array $brands = [];

    #[Url]
    public ?float $minPrice = null;

    #[Url]
    public ?float $maxPrice = null;

    #[Url]
    public array $ratings = [];

    #[Url]
    public string $sort = 'featured';

    #[Url]
    public int $perPage = 10;

    public function mount(): void
    {
        $this->sanitizePerPage();
        $this->sanitizeSort();
    }

    public function updatedPerPage(): void
    {
        $this->sanitizePerPage();
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->sanitizeSort();
        $this->resetPage();
    }

    public function getProductsProperty(): LengthAwarePaginator
    {
        $query = Product::query()
            ->with([
                'defaultUrl',
                'variants.basePrices.currency',
                'brand',
                'tags',
                'collections',
                'images',
                'thumbnail',
            ])
            ->whereStatus(ProductStatus::Published);

        if (! empty($this->categories)) {
            $query->whereHas('collections', function ($q) {
                $q->whereIn((new Models\Collection)->getTable().'.id', $this->categories);
            });
        }

        if (! empty($this->brands)) {
            $query->whereIn('brand_id', $this->brands);
        }

        if ($this->minPrice !== null || $this->maxPrice !== null) {
            $query->whereHas('variants.basePrices', function ($q) {
                if ($this->minPrice !== null) {
                    $q->where('price', '>=', $this->minPrice * 100);
                }
                if ($this->maxPrice !== null) {
                    $q->where('price', '<=', $this->maxPrice * 100);
                }
            });
        }

        if (! empty($this->ratings)) {
            $query->where(function ($q) {
                foreach ($this->ratings as $rating) {
                    $q->orWhere('rating', '>=', $rating);
                }
            });
        }

        switch ($this->sort) {
            case 'price_asc':
                $this->applyPriceSorting($query, 'asc');
                break;
            case 'price_desc':
                $this->applyPriceSorting($query, 'desc');
                break;
            case 'date':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'featured':
            default:
                // For featured, we could use a custom field or just id
                $query->orderBy('id', 'desc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getAllCategoriesProperty(): Collection
    {
        return Models\Collection::all();
    }

    public function getAllBrandsProperty(): Collection
    {
        return Brand::all();
    }

    public function getMaxPriceValueProperty(): float
    {
        $max = Price::query()->max('price');

        return $max ? ceil($max / 100) : 1000;
    }

    public function resetFilters(): void
    {
        $this->reset(['categories', 'brands', 'minPrice', 'maxPrice', 'ratings', 'sort', 'perPage']);
        $this->resetPage();
    }

    protected function applyPriceSorting(Builder $query, string $direction): void
    {
        $priceAggregation = Price::query()
            ->join('store_product_variants', 'store_product_variants.id', '=', 'store_prices.priceable_id')
            ->where('store_prices.priceable_type', (new ProductVariant)->getMorphClass())
            ->selectRaw('store_product_variants.product_id as product_id')
            ->selectRaw(
                $direction === 'asc'
                    ? 'MIN(store_prices.price) as sort_price'
                    : 'MAX(store_prices.price) as sort_price'
            )
            ->groupBy('store_product_variants.product_id');

        $query->leftJoinSub($priceAggregation, 'product_price_sort', function ($join) {
            $join->on('store_products.id', '=', 'product_price_sort.product_id');
        })->orderByRaw('product_price_sort.sort_price IS NULL')
            ->orderBy('product_price_sort.sort_price', $direction)
            ->orderBy('store_products.id', 'desc')
            ->select('store_products.*');
    }

    protected function sanitizePerPage(): void
    {
        if (! in_array($this->perPage, self::PER_PAGE_OPTIONS, true)) {
            $this->perPage = self::DEFAULT_PER_PAGE;
        }
    }

    protected function sanitizeSort(): void
    {
        if (! in_array($this->sort, self::SORT_OPTIONS, true)) {
            $this->sort = self::DEFAULT_SORT;
        }
    }

    public function render()
    {
        return view('livewire.shop-grid', [
            'products' => $this->products,
            'allCategories' => $this->allCategories,
            'allBrands' => $this->allBrands,
            'maxPriceValue' => $this->maxPriceValue,
        ]);
    }
}
