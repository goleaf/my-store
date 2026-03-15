<?php

use App\Models\Product;
use Lunar\Models\Product as LunarProduct;

test('product extends Lunar product', function () {
    expect(Product::class)->toExtend(LunarProduct::class);
});

test('product can be instantiated', function () {
    $product = new Product;

    expect($product)->toBeInstanceOf(Product::class)
        ->toBeInstanceOf(LunarProduct::class);
});
