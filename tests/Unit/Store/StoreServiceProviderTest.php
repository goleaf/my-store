<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('store config is merged', function () {
    expect(config('store.cart'))->toBeArray();
    expect(config('store.products'))->toBeArray();
});

test('store facades are resolvable', function () {
    expect(app(\App\Facades\CartSession::class))->toBeObject();
    expect(app(\App\Facades\Payments::class))->toBeObject();
});
