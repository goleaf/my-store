<?php

use App\Store\Models\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('store collection model exists', function () {
    expect(class_exists(Collection::class))->toBeTrue();
});

test('store collection has products relationship', function () {
    $collection = new Collection;
    expect(method_exists($collection, 'products'))->toBeTrue();
});
