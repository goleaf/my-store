<?php

use App\Livewire\Components\Cart;
use Livewire\Livewire;
use Lunar\Facades\CartSession;
use Lunar\Models\Cart as LunarCart;
use Lunar\Models\Language;

test('component can mount', function () {
    Language::factory()->create(['default' => true]);
    $cart = LunarCart::factory()->create()->calculate();
    CartSession::shouldReceive('current')->andReturn($cart);

    Livewire::test(Cart::class)
        ->assertViewIs('livewire.components.cart');
});

test('component maps empty lines when cart has no lines', function () {
    Language::factory()->create(['default' => true]);
    $cart = LunarCart::factory()->create()->calculate();
    CartSession::shouldReceive('current')->andReturn($cart);

    $component = Livewire::test(Cart::class);

    expect($component->get('lines'))->toBeArray()->toBeEmpty();
});

test('removeLine calls CartSession remove', function () {
    Language::factory()->create(['default' => true]);
    $cart = LunarCart::factory()->create()->calculate();
    CartSession::shouldReceive('current')->andReturn($cart);
    CartSession::shouldReceive('remove')->with(123)->once();

    Livewire::test(Cart::class)
        ->call('removeLine', 123);
});
