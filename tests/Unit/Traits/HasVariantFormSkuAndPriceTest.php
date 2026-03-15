<?php

use App\Filament\Resources\ProductResource\Widgets\ProductOptionsWidget;
use App\Traits\HasVariantFormSkuAndPrice;

test('variantFormFieldsOrder returns options sku price stock', function () {
    $order = ProductOptionsWidget::variantFormFieldsOrder();

    expect($order)->toBe(['options', 'sku', 'price', 'stock']);
});

test('normalizeVariantRowForSave returns expected keys', function () {
    $trait = new class {
        use HasVariantFormSkuAndPrice;
    };

    $row = $trait->normalizeVariantRowForSave([
        'sku' => 'SKU-1',
        'price' => '9.99',
        'stock' => 10,
        'values' => [1, 2],
    ]);

    expect($row)->toHaveKeys(['sku', 'price', 'stock', 'values', 'variant_id', 'copied_id'])
        ->and($row['sku'])->toBe('SKU-1')
        ->and($row['price'])->toBe('9.99')
        ->and($row['stock'])->toBe(10);
});

test('normalizeVariantRowForSave fills missing keys with defaults', function () {
    $trait = new class {
        use HasVariantFormSkuAndPrice;
    };

    $row = $trait->normalizeVariantRowForSave([]);

    expect($row['sku'])->toBe('')
        ->and($row['price'])->toBe('0')
        ->and($row['stock'])->toBe(0)
        ->and($row['values'])->toBe([]);
});
