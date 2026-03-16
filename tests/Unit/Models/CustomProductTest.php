<?php

use App\Models\CustomProduct;
use App\Models\Product as StoreProduct;

test('custom product extends store product', function () {
    expect(CustomProduct::class)->toExtend(StoreProduct::class);
});

test('custom product table name contains products', function () {
    $product = new CustomProduct;

    expect($product->getTable())->toContain('products');
});
