<?php

namespace App\Livewire;

use App\Base\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Facades\CartSession;
use App\Traits\CanAddToCart;
use App\Traits\CanManageWishlist;
use Filament\Notifications\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models;

class ShopGrid extends Component
{
    use WithPagination;
    use CanAddToCart;
    use CanManageWishlist;

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
                $query->join('store_product_variants', 'store_products.id', '=', 'store_product_variants.product_id')
                    ->join('store_prices', 'store_product_variants.id', '=', 'store_prices.priceable_id')
                    ->where('store_prices.priceable_type', (new ProductVariant)->getMorphClass())
                    ->orderBy('store_prices.price', 'asc')
                    ->select('store_products.*');
                break;
            case 'price_desc':
                $query->join('store_product_variants', 'store_products.id', '=', 'store_product_variants.product_id')
                    ->join('store_prices', 'store_product_variants.id', '=', 'store_prices.priceable_id')
                    ->where('store_prices.priceable_type', (new ProductVariant)->getMorphClass())
                    ->orderBy('store_prices.price', 'desc')
                    ->select('store_products.*');
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
        $max = DB::table('store_prices')->max('price');
        return $max ? ceil($max / 100) : 1000;
    }

    public function resetFilters(): void
    {
        $this->reset(['categories', 'brands', 'minPrice', 'maxPrice', 'ratings', 'sort', 'perPage']);
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
