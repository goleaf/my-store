<?php

use App\Models\Language;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Language::factory()->create([
        'default' => true,
        'code' => 'en',
    ]);
});

it('returns an empty string if product name is missing', function () {
    $product = Product::factory()->create();
    $product->attribute_data = null;
    $product->save();

    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
    ]);

    expect($variant->getDescription())->toBeString()->toBeEmpty();
});

it('returns an empty string if product relationship is null', function () {
    $variant = new ProductVariant();
    
    expect($variant->getDescription())->toBeString()->toBeEmpty();
});

it('returns an empty string if sku is null', function () {
    $variant = ProductVariant::factory()->make([
        'sku' => null,
    ]);

    expect($variant->getIdentifier())->toBeString()->toBeEmpty();
});
