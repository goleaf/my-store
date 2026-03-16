<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Facades\CartSession;
use App\Facades\Payments;

uses(RefreshDatabase::class);

test('store config is merged', function () {
    expect(config('store.cart'))->toBeArray();
    expect(config('store.products'))->toBeArray();
});

test('store facades are resolvable', function () {
    expect(app(CartSession::class))->toBeObject();
    expect(app(Payments::class))->toBeObject();
});
