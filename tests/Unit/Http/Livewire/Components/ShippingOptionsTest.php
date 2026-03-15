<?php

use App\Livewire\Components\ShippingOptions;
use Livewire\Livewire;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\DataTypes\ShippingOption;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;

test('component can mount', function () {
    $cart = Cart::factory()->create();
    $cart->setRelation('shippingAddress', null);
    CartSession::shouldReceive('current')->andReturn($cart);
    CartSession::shouldReceive('getCart')->andReturn($cart);
    ShippingManifest::shouldReceive('getOptions')->with($cart)->andReturn(collect());

    Livewire::test(ShippingOptions::class)
        ->assertViewIs('livewire.components.shipping-options');
});

test('save validates chosenOption and dispatches event', function () {
    TaxClass::factory()->create(['default' => true]);
    $currency = Currency::factory()->create(['default' => true]);
    $cart = Cart::factory()->create();
    $option = new ShippingOption(
        name: 'Test',
        description: 'Test',
        identifier: 'TEST',
        price: new Price(500, $currency, 1),
        taxClass: TaxClass::getDefault()
    );
    $options = collect([$option]);

    CartSession::shouldReceive('current')->andReturn($cart);
    CartSession::shouldReceive('getCart')->andReturn($cart);
    ShippingManifest::shouldReceive('getOptions')->with($cart)->andReturn($options);
    CartSession::shouldReceive('setShippingOption')->with($option)->once();

    Livewire::test(ShippingOptions::class)
        ->set('chosenOption', 'TEST')
        ->call('save')
        ->assertDispatched('selectedShippingOption');
});
