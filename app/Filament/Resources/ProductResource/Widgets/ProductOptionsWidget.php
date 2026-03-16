<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Traits\HasVariantFormSkuAndPrice;
use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Facades\DB;
use App\Models\ProductVariant;
use App\Events\ProductVariantOptionsUpdated;
use App\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Models\Contracts\ProductOption as ProductOptionContract;
use Illuminate\Database\Eloquent\Model;

class ProductOptionsWidget extends Widget
{
    public ?Model $record = null;

    public bool $configuringOptions = false;

    public array $configuredOptions = [];

    public array $variants = [];

    public bool $hasNewVariants = false;

    protected string $view = 'admin::resources.product-resource.widgets.product-options';

    use HasVariantFormSkuAndPrice;

    public function mount()
    {
        $this->record = $this->record;
        $this->configuredOptions = $this->record->productOptions()->withPivot('position')->get()->map(function ($option) {
            return [
                'id' => $option->id,
                'value' => $option->translate('name'),
                'position' => $option->pivot->position,
                'option_values' => $option->values->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->translate('name'),
                        'enabled' => $this->record->variants()->whereHas('values', fn ($q) => $q->where('product_option_value_id', $value->id))->exists(),
                    ];
                })->toArray(),
            ];
        })->toArray();

        $this->variants = $this->record->variants->map(function ($variant) {
            return [
                'variant_id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->basePrices->first()?->price->decimal ?? 0,
                'stock' => $variant->stock,
                'values' => $variant->values->mapWithKeys(function ($value) {
                    return [$value->option->translate('name') => $value->translate('name')];
                })->toArray(),
            ];
        })->toArray();
    }

    public function storeConfiguredOptions()
    {
        // This method is called in the original class.
        // We'll just leave it empty or implement what's needed.
    }

    public function mapOptionValuesToIds($values)
    {
        // Logic to map option value names to IDs
        return \App\Models\ProductOptionValue::whereIn('id', array_keys($values))->pluck('id')->toArray();
    }

    /**
     * Save variants with SKU as second field and price for variant via app trait.
     */
    public function saveVariantsAction()
    {
        return Action::make('saveVariants')
            ->action(function () {
                DB::beginTransaction();

                $this->storeConfiguredOptions();

                if (! count($this->variants)) {
                    $variant = $this->record->variants()->first();
                    $variant->values()->detach();
                    $this->record->productOptions()->exclusive()->each(
                        fn (ProductOptionContract $productOption) => $productOption->delete()
                    );
                    $this->record->productOptions()->shared()->detach();
                    $this->record->variants()
                        ->where('id', '!=', $variant->id)
                        ->get()
                        ->each(
                            fn (ProductVariantContract $v) => $v->delete()
                        );
                    DB::commit();
                    Notification::make()
                        ->title(__('admin::productoption.widgets.product-options.notifications.save-variants.success.title'))
                        ->success()
                        ->send();
                    return;
                }

                foreach ($this->variants as $variantIndex => $variantData) {
                    $row = $this->normalizeVariantRowForSave($variantData);

                    $variant = new ProductVariant(['product_id' => $this->record->id]);
                    if (! empty($variantData['variant_id'])) {
                        $variant = ProductVariant::find($variantData['variant_id']);
                    }

                    if (! empty($variantData['copied_id'])) {
                        $copiedVariant = ProductVariant::find($variantData['copied_id']);
                        $variant = $copiedVariant->replicate();
                        $variant->sku = $row['sku'];
                        $variant->stock = $row['stock'];
                        $variant->save();
                        $copiedBasePrice = $copiedVariant->basePrices->first();
                        if ($copiedBasePrice) {
                            $basePrice = $copiedBasePrice->replicate();
                            $basePrice->priceable_id = $variant->id;
                            $basePrice->price = (int) bcmul($row['price'], $basePrice->currency->factor);
                            $basePrice->save();
                        }
                    } else {
                        $this->persistVariantSkuAndPrice($variant, $row);
                        $basePrice = $variant->basePrices->first();
                        if (! $basePrice) {
                            $this->ensureBasePriceForVariant($variant, $row['price']);
                        } else {
                            $basePrice->price = (int) bcmul($row['price'], $basePrice->currency->factor);
                            $basePrice->save();
                        }
                    }

                    $optionsValues = $this->mapOptionValuesToIds($variantData['values']);
                    $variant->values()->sync($optionsValues);
                    $this->variants[$variantIndex]['variant_id'] = $variant->id;
                }

                $productOptions = collect($this->configuredOptions)
                    ->mapWithKeys(fn ($option) => [$option['id'] => ['position' => $option['position']]]);
                $this->record->productOptions()->sync($productOptions);

                $variantIds = collect($this->variants)->pluck('variant_id');
                $this->record->variants()->whereNotIn('id', $variantIds)->get()
                    ->each(fn (ProductVariantContract $v) => $v->delete());

                DB::commit();
                Notification::make()
                    ->title(__('admin::productoption.widgets.product-options.notifications.save-variants.success.title'))
                    ->success()
                    ->send();
            })
            ->after(fn () => ProductVariantOptionsUpdated::dispatch($this->record));
    }
}
