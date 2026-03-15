<?php

namespace App\Livewire;

use App\Traits\FetchesUrls;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Lunar\Models\Price;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductPage extends Component
{
    use FetchesUrls;

    /**
     * The selected option values.
     */
    public array $selectedOptionValues = [];

    public function mount($slug): void
    {
        $this->url = $this->fetchUrl(
            $slug,
            (new Product)->getMorphClass(),
            [
                'element.media',
                'element.brand',
                'element.productType',
                'element.tags',
                'element.collections.defaultUrl',
                'element.collections.group',
                'element.associations.target.defaultUrl',
                'element.associations.target.thumbnail',
                'element.variants.basePrices.currency',
                'element.variants.basePrices.priceable',
                'element.variants.values.option',
                'element.variants.taxClass',
                'element.variants.images',
            ]
        );

        if (! $this->url) {
            abort(404);
        }

        $this->selectedOptionValues = $this->productOptions->mapWithKeys(function ($data) {
            $first = $data['values']->first();
            return [$data['option']->id => $first ? $first->id : null];
        })->filter()->toArray();
    }

    /**
     * Computed property to get variant.
     */
    public function getVariantProperty(): ProductVariant
    {
        $variant = $this->product->variants->first(function ($variant) {
            return ! $variant->values->pluck('id')
                ->diff(
                    collect($this->selectedOptionValues)->values()
                )->count();
        });

        return $variant ?? $this->product->variants->first();
    }

    /**
     * Computed property to return all available option values.
     */
    public function getProductOptionValuesProperty(): Collection
    {
        return $this->product->variants->pluck('values')->flatten();
    }

    /**
     * Computed propert to get available product options with values.
     */
    public function getProductOptionsProperty(): Collection
    {
        return $this->productOptionValues->unique('id')->groupBy('product_option_id')
            ->map(function ($values) {
                return [
                    'option' => $values->first()->option,
                    'values' => $values,
                ];
            })->values();
    }

    /**
     * Computed property to return product.
     */
    public function getProductProperty(): Product
    {
        return $this->url->element;
    }

    /**
     * Return all images for the product.
     */
    public function getImagesProperty(): Collection
    {
        return $this->product->media->sortBy('order_column');
    }

    /**
     * Computed property to return current image.
     */
    public function getImageProperty(): ?Media
    {
        if ($this->variant->images && $this->variant->images->isNotEmpty()) {
            return $this->variant->images->first();
        }

        if ($primary = $this->images->first(fn ($media) => $media->getCustomProperty('primary'))) {
            return $primary;
        }

        return $this->images->first();
    }

    /** Base price (first base price for current variant). */
    public function getBasePriceProperty(): ?Price
    {
        return $this->variant->basePrices->first();
    }

    /** Compare price (same as base price model; may have compare_price value). */
    public function getComparePriceValueProperty(): mixed
    {
        $base = $this->basePrice;
        if (! $base || ! $base->compare_price) {
            return null;
        }
        return $base->compare_price;
    }

    /** Variant identifiers (SKU, GTIN, EAN, MPN, tax_ref) for display. */
    public function getVariantIdentifiersProperty(): array
    {
        $v = $this->variant;
        return array_filter([
            'sku' => $v->sku,
            'gtin' => $v->gtin ?? null,
            'ean' => $v->ean ?? null,
            'mpn' => $v->mpn ?? null,
            'tax_ref' => $v->tax_ref ?? null,
        ]);
    }

    /** Variant dimensions and weight for display. */
    public function getVariantDimensionsProperty(): array
    {
        $v = $this->variant;
        return array_filter([
            'length' => $v->length_value ? ($v->length_value . ' ' . ($v->length_unit ?? '')) : null,
            'width' => $v->width_value ? ($v->width_value . ' ' . ($v->width_unit ?? '')) : null,
            'height' => $v->height_value ? ($v->height_value . ' ' . ($v->height_unit ?? '')) : null,
            'weight' => $v->weight_value ? ($v->weight_value . ' ' . ($v->weight_unit ?? '')) : null,
            'volume' => $v->volume_value ? ($v->volume_value . ' ' . ($v->volume_unit ?? '')) : null,
        ]);
    }

    /** Product attribute_data keys that have values (for generic display). */
    public function getProductAttributeDataProperty(): array
    {
        $data = $this->product->attribute_data;
        if (! $data || ! is_iterable($data)) {
            return [];
        }
        $out = [];
        foreach ($data as $key => $field) {
            $value = $this->product->translateAttribute($key);
            if ($value !== null && $value !== '') {
                $out[$key] = $value;
            }
        }
        return $out;
    }

    /** Variant attribute_data keys that have values. */
    public function getVariantAttributeDataProperty(): array
    {
        $data = $this->variant->attribute_data;
        if (! $data || ! is_iterable($data)) {
            return [];
        }
        $out = [];
        foreach ($data as $key => $field) {
            $value = $this->variant->translateAttribute($key);
            if ($value !== null && $value !== '') {
                $out[$key] = $value;
            }
        }
        return $out;
    }

    /** Associated products (cross-sell, up-sell, alternate) with type. */
    public function getAssociationsProperty(): Collection
    {
        return $this->product->associations()->with(['target.defaultUrl', 'target.thumbnail'])->get();
    }

    public function render(): View
    {
        return view('livewire.product-page');
    }
}
