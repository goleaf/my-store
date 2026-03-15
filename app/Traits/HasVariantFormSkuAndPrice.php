<?php

namespace App\Traits;

use App\Store\Models\Currency;
use App\Store\Models\Price;
use App\Store\Models\ProductVariant;

trait HasVariantFormSkuAndPrice
{
    /**
     * Variant form field order: option values first, then SKU (second field), then price for variant, then stock.
     *
     * @return array<int, string>
     */
    public static function variantFormFieldsOrder(): array
    {
        return ['options', 'sku', 'price', 'stock'];
    }

    /**
     * Ensure a variant row from the form has sku and price keys for saving.
     */
    public function normalizeVariantRowForSave(array $row): array
    {
        return [
            'sku' => $row['sku'] ?? '',
            'price' => isset($row['price']) ? (string) $row['price'] : '0',
            'stock' => (int) ($row['stock'] ?? 0),
            'values' => $row['values'] ?? [],
            'variant_id' => $row['variant_id'] ?? null,
            'copied_id' => $row['copied_id'] ?? null,
        ];
    }

    /**
     * Persist SKU on the variant (second field in form) and price for variant.
     */
    public function persistVariantSkuAndPrice(ProductVariant $variant, array $row): void
    {
        $variant->sku = $row['sku'] ?? $variant->sku;
        $variant->stock = (int) ($row['stock'] ?? $variant->stock);
        $variant->save();

        $basePrice = $variant->basePrices->first();
        if ($basePrice && isset($row['price'])) {
            $priceValue = (string) $row['price'];
            $basePrice->price = (int) bcmul($priceValue, $basePrice->currency->factor);
            $basePrice->save();
        }
    }

    /**
     * Create a new base price for a variant when none exists (e.g. new variant).
     */
    public function ensureBasePriceForVariant(ProductVariant $variant, string $priceDecimal): ?Price
    {
        $basePrice = $variant->basePrices->first();
        if ($basePrice) {
            return $basePrice;
        }
        $currency = Currency::getDefault();
        if (! $currency) {
            return null;
        }
        $basePrice = new Price([
            'currency_id' => $currency->id,
            'priceable_type' => $variant->getMorphClass(),
            'priceable_id' => $variant->id,
            'min_quantity' => 1,
            'customer_group_id' => null,
        ]);
        $basePrice->price = (int) bcmul($priceDecimal, $currency->factor);
        $basePrice->save();

        return $basePrice;
    }
}
