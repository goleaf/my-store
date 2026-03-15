<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Filament\Resources\ProductResource\Widgets\ProductOptionsWidget as LunarProductOptionsWidget;
use App\Events\ProductVariantOptionsUpdated;
use App\Store\Facades\DB;
use App\Store\Models\Contracts\ProductOption as ProductOptionContract;
use App\Store\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Store\Models\ProductVariant;
use App\Traits\HasVariantFormSkuAndPrice;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ProductOptionsWidget extends LunarProductOptionsWidget
{
    use HasVariantFormSkuAndPrice;

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
