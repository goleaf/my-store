<?php

use App\Livewire\Components\AddToCart;
use Livewire\Livewire;
use App\Facades\CartSession;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Price;
use App\Models\Language;

test('component can mount', function () {
    Livewire::test(AddToCart::class, ['purchasable' => null])
        ->assertViewIs('livewire.components.add-to-cart');
});

test('addToCart validates quantity', function () {
    Language::factory()->create(['default' => true]);
    $currency = Currency::factory()->create(['default' => true]);
    $product = Product::factory()->hasUrls(1, ['default' => true])->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
    $variant->prices()->create(
        Price::factory()->make(['currency_id' => $currency->id])->getAttributes()
    );

    Livewire::test(AddToCart::class, ['purchasable' => $variant])
        ->set('quantity', 0)
        ->call('addToCart')
        ->assertHasErrors('quantity');
});

test('addToCart dispatches event when valid', function () {
    Language::factory()->create(['default' => true]);
    $currency = Currency::factory()->create(['default' => true]);
    $product = Product::factory()->hasUrls(1, ['default' => true])->create();
    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
        'stock' => 100,
    ]);
    $variant->prices()->create(
        Price::factory()->make(['currency_id' => $currency->id])->getAttributes()
    );

    $cart = \App\Models\Cart::factory()->create();
    CartSession::shouldReceive('manager')->andReturn($cart);

    Livewire::test(AddToCart::class, ['purchasable' => $variant])
        ->set('quantity', 1)
        ->call('addToCart')
        ->assertDispatched('add-to-cart');
});
