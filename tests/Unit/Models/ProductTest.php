<?php

use App\Models\Product;
use App\Models\Product as StoreProduct;

test('product extends store product', function () {
    expect(Product::class)->toExtend(StoreProduct::class);
});

test('product can be instantiated', function () {
    $product = new Product;

    expect($product)->toBeInstanceOf(Product::class)
        ->toBeInstanceOf(StoreProduct::class);
});
