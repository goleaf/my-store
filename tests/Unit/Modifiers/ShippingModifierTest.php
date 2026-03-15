<?php

use App\Modifiers\ShippingModifier;
use Illuminate\Support\Facades\Config;
use App\Store\Facades\ShippingManifest;
use App\Store\Models\Cart;
use App\Store\Models\Currency;
use App\Store\Models\Channel;
use App\Store\Models\TaxClass;

test('modifier adds Basic Delivery option when shipping-tables disabled', function () {
    Config::set('shipping-tables.enabled', false);
    TaxClass::factory()->create(['default' => true]);

    $cart = Cart::factory()->create();
    $next = fn ($c) => $c;
    $modifier = new ShippingModifier;

    $result = $modifier->handle($cart, $next);

    expect($result)->toBe($cart);
    $options = ShippingManifest::getOptions($cart);
    expect($options->isNotEmpty())->toBeTrue();
    expect($options->first(fn ($o) => $o->getIdentifier() === 'BASDEL'))->not->toBeNull();
});

test('modifier passes through when shipping-tables enabled', function () {
    Config::set('shipping-tables.enabled', true);

    $cart = Cart::factory()->create();
    $next = fn ($c) => $c;
    $modifier = new ShippingModifier;

    $result = $modifier->handle($cart, $next);

    expect($result)->toBe($cart);
});
