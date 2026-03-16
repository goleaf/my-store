<?php

use App\Livewire\CheckoutSuccessPage;
use Livewire\Livewire;
use App\Facades\CartSession;
use App\Models\Cart;
use App\Models\Order;

test('component redirects when no cart', function () {
    CartSession::shouldReceive('current')->andReturn(null);

    Livewire::test(CheckoutSuccessPage::class)
        ->assertRedirect('/');
});

test('component redirects when cart has no completed order', function () {
    $cart = Cart::factory()->create();
    $cart->setRelation('completedOrder', null);
    CartSession::shouldReceive('current')->andReturn($cart);

    Livewire::test(CheckoutSuccessPage::class)
        ->assertRedirect('/');
});

test('component mounts and shows order when cart has completed order', function () {
    $cart = Cart::factory()->create();
    $order = Order::factory()->create([
        'cart_id' => $cart->id,
        'placed_at' => now(),
    ]);

    CartSession::shouldReceive('current')->andReturn($cart);
    CartSession::shouldReceive('forget')->once();

    Livewire::test(CheckoutSuccessPage::class)
        ->assertViewIs('livewire.checkout-success-page')
        ->assertSet('order.id', $order->id)
        ->assertSet('cart.id', $cart->id);
});
