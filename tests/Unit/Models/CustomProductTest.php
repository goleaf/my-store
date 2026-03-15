<?php

use App\Models\CustomProduct;
use Lunar\Models\Product as LunarProduct;

test('custom product extends Lunar product', function () {
    expect(CustomProduct::class)->toExtend(LunarProduct::class);
});

test('custom product table name contains products', function () {
    $product = new CustomProduct;

    expect($product->getTable())->toContain('products');
});
