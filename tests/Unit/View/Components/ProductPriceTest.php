<?php

use App\View\Components\ProductPrice;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Lunar\Models\Currency;
use Lunar\Models\Price;
use Lunar\Models\Language;

test('component renders with null product and variant', function () {
    $component = new ProductPrice(null, null, false);

    expect($component->variant)->toBeNull()
        ->and($component->price)->toBeNull();
});

test('comparePrice returns null when showCompare is false', function () {
    $component = new ProductPrice(null, null, false);

    expect($component->comparePrice())->toBeNull();
});

test('component resolves price from variant', function () {
    Language::factory()->create(['default' => true]);
    $currency = Currency::factory()->create(['default' => true]);
    $product = Product::factory()->hasUrls(1, ['default' => true])->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
    $variant->prices()->create(
        Price::factory()->make(['currency_id' => $currency->id])->getAttributes()
    );

    $component = new ProductPrice($product, $variant, false);

    expect($component->variant)->toBe($variant);
});
