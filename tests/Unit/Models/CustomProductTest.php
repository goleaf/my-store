<?php

use App\Models\CustomProduct;
use App\Models\Product;

test('custom product extends store product', function () {
    expect(CustomProduct::class)->toExtend(Product::class);
});

test('custom product table name contains products', function () {
    $product = new CustomProduct;

    expect($product->getTable())->toContain('products');
});
