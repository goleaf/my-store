<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('store product model exists', function () {
    expect(Product::class)->toBeString()
        ->and(class_exists(Product::class))->toBeTrue();
});

test('store product has morph name', function () {
    $product = new Product;
    expect($product->getMorphClass())->not->toBeEmpty();
});
