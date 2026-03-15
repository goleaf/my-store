<?php

use App\Livewire\Components\CheckoutAddress;
use Livewire\Livewire;
use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\CartAddress;
use Lunar\Models\Country;

test('component can mount with billing type', function () {
    $cart = Cart::factory()->create();
    $cart->setRelation('addresses', collect());
    CartSession::shouldReceive('current')->andReturn($cart);

    Livewire::test(CheckoutAddress::class, ['type' => 'billing'])
        ->assertViewIs('livewire.components.checkout-address')
        ->assertSet('type', 'billing');
});

test('component can mount with shipping type', function () {
    $cart = Cart::factory()->create();
    $cart->setRelation('addresses', collect());
    CartSession::shouldReceive('current')->andReturn($cart);

    Livewire::test(CheckoutAddress::class, ['type' => 'shipping'])
        ->assertSet('type', 'shipping');
});

test('countries property returns GBR and USA', function () {
    Country::factory()->create(['iso3' => 'GBR', 'name' => 'United Kingdom']);
    Country::factory()->create(['iso3' => 'USA', 'name' => 'United States']);
    $cart = Cart::factory()->create();
    $cart->setRelation('addresses', collect());
    CartSession::shouldReceive('current')->andReturn($cart);

    $component = Livewire::test(CheckoutAddress::class, ['type' => 'billing']);
    $countries = $component->get('countries');

    expect($countries->pluck('iso3')->toArray())->toContain('GBR', 'USA');
});
