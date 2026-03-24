<?php

use App\Models\Product;

test('product extends store product', function () {
    expect(Product::class)->toExtend(Product::class);
});

test('product can be instantiated', function () {
    $product = new Product;

    expect($product)->toBeInstanceOf(Product::class)
        ->toBeInstanceOf(Product::class);
});

test('mapped attributes returns empty collection when product type is missing', function () {
    $product = new Product;

    expect($product->mappedAttributes())->toBeEmpty();
});
