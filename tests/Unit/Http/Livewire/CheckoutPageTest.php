<?php

use App\Livewire\CheckoutPage;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use App\Facades\CartSession;
use App\Models\Cart;
use App\Models\CartAddress;
use App\Models\Country;
use App\Models\TaxClass;
use App\Models\TaxZone;

test('component can mount', function () {
    CartSession::shouldReceive('current')->andReturn(
        Cart::factory()->create()->calculate()
    );

    Livewire::test(CheckoutPage::class)
        ->assertViewIs('livewire.checkout-page');
});

test('checkout step is correct on load', function () {
    CartSession::shouldReceive('current')->andReturn(
        Cart::factory()->create()->calculate()
    );

    Livewire::test(CheckoutPage::class)
        ->assertViewIs('livewire.checkout-page')
        ->assertSet('currentStep', 1);
})->group('moomoo');

test('checkout step is correct with shipping on load', function () {
    TaxClass::factory()->create([
        'default' => true,
    ]);

    $cart = Cart::factory()->create();

    $cart->addresses()->create(
        CartAddress::factory()->make([
            'type' => 'shipping',
        ])->toArray()
    );

    CartSession::shouldReceive('current')->andReturn(
        $cart->calculate()
    );

    Livewire::test(CheckoutPage::class)
        ->assertViewIs('livewire.checkout-page')
        ->assertSet('currentStep', 2);
});

test('checkout on billing if we have shipping option', function () {
    Config::set('shipping-tables.enabled', false);

    TaxClass::factory()->create([
        'default' => true,
    ]);

    TaxZone::factory()->create([
        'default' => true,
    ]);

    $cart = Cart::factory()->create();

    $cart->addresses()->create(
        CartAddress::factory()->make([
            'type' => 'shipping',
            'shipping_option' => 'BASDEL',
        ])->toArray()
    );

    CartSession::shouldReceive('current')->andReturn(
        $cart->calculate()
    );

    Livewire::test(CheckoutPage::class)
        ->assertViewIs('livewire.checkout-page')
        ->assertSet('currentStep', 3);
});

test('checkout on payment if we have billing address', function () {
    Config::set('shipping-tables.enabled', false);

    TaxClass::factory()->create([
        'default' => true,
    ]);

    TaxZone::factory()->create([
        'default' => true,
    ]);

    $cart = Cart::factory()->create();

    $cart->addresses()->create(
        CartAddress::factory()->make([
            'type' => 'shipping',
            'shipping_option' => 'BASDEL',
        ])->toArray()
    );

    $cart->addresses()->create(
        CartAddress::factory()->make([
            'type' => 'billing',
        ])->toArray()
    );

    CartSession::shouldReceive('current')->andReturn(
        $cart->calculate()
    );

    Livewire::test(CheckoutPage::class)
        ->set('paymentType', 'cash')
        ->assertViewIs('livewire.checkout-page')
        ->assertSet('currentStep', 4);
});

test('can save shipping address', function () {
    TaxClass::factory()->create([
        'default' => true,
    ]);

    TaxZone::factory()->create([
        'default' => true,
    ]);

    $cart = Cart::factory()->create();

    CartSession::shouldReceive('getCart')->andReturn(
        $cart->calculate()
    );

    CartSession::shouldReceive('current')->andReturn(
        $cart->calculate()
    );

    $country = Country::factory()->create();

    Livewire::test(CheckoutPage::class)
        ->set('paymentType', 'cash')
        ->assertViewIs('livewire.checkout-page')
        ->call('saveAddress', 'shipping')
        ->assertHasErrors([
            'shipping.first_name' => 'required',
            'shipping.last_name' => 'required',
            'shipping.line_one' => 'required',
            'shipping.country_id' => 'required',
            'shipping.city' => 'required',
            'shipping.postcode' => 'required',
            'shipping.contact_email' => 'required',
        ])
        ->set('shipping.first_name', 'Tony')
        ->set('shipping.last_name', 'Stark')
        ->set('shipping.company_name', 'Stark Industries')
        ->set('shipping.line_one', '1200 Industrial Ave')
        ->set('shipping.line_two', null)
        ->set('shipping.line_three', null)
        ->set('shipping.city', 'Long Beach')
        ->set('shipping.state', 'CA')
        ->set('shipping.postcode', '90803')
        ->set('shipping.delivery_instructions', 'Press the buzzer')
        ->set('shipping.contact_email', 'deliveries@stark.co')
        ->set('shipping.contact_phone', '123123123')
        ->set('shipping.country_id', $country->id)
        ->call('saveAddress', 'shipping')
        ->assertHasNoErrors();

    $this->assertDatabaseHas((new CartAddress)->getTable(), [
        'first_name' => 'Tony',
        'last_name' => 'Stark',
        'company_name' => 'Stark Industries',
        'line_one' => '1200 Industrial Ave',
        'city' => 'Long Beach',
        'state' => 'CA',
        'postcode' => '90803',
        'delivery_instructions' => 'Press the buzzer',
        'contact_email' => 'deliveries@stark.co',
        'contact_phone' => '123123123',
        'country_id' => $country->id,
        'type' => 'shipping',
    ]);
});

test('can save billing address', function () {
    TaxClass::factory()->create([
        'default' => true,
    ]);

    TaxZone::factory()->create([
        'default' => true,
    ]);

    $cart = Cart::factory()->create();

    CartSession::shouldReceive('getCart')->andReturn(
        $cart->calculate()
    );

    CartSession::shouldReceive('current')->andReturn(
        $cart->calculate()
    );

    $country = Country::factory()->create();

    Livewire::test(CheckoutPage::class)
        ->set('paymentType', 'cash')
        ->assertViewIs('livewire.checkout-page')
        ->call('saveAddress', 'billing')
        ->assertHasErrors([
            'billing.first_name' => 'required',
            'billing.last_name' => 'required',
            'billing.line_one' => 'required',
            'billing.country_id' => 'required',
            'billing.city' => 'required',
            'billing.postcode' => 'required',
            'billing.contact_email' => 'required',
        ])
        ->set('billing.first_name', 'Tony')
        ->set('billing.last_name', 'Stark')
        ->set('billing.company_name', 'Stark Industries')
        ->set('billing.line_one', '1200 Industrial Ave')
        ->set('billing.line_two', null)
        ->set('billing.line_three', null)
        ->set('billing.city', 'Long Beach')
        ->set('billing.state', 'CA')
        ->set('billing.postcode', '90803')
        ->set('billing.delivery_instructions', 'Press the buzzer')
        ->set('billing.contact_email', 'deliveries@stark.co')
        ->set('billing.contact_phone', '123123123')
        ->set('billing.country_id', $country->id)
        ->call('saveAddress', 'billing')
        ->assertHasNoErrors();

    $this->assertDatabaseHas((new CartAddress)->getTable(), [
        'first_name' => 'Tony',
        'last_name' => 'Stark',
        'company_name' => 'Stark Industries',
        'line_one' => '1200 Industrial Ave',
        'city' => 'Long Beach',
        'state' => 'CA',
        'postcode' => '90803',
        'delivery_instructions' => 'Press the buzzer',
        'contact_email' => 'deliveries@stark.co',
        'contact_phone' => '123123123',
        'country_id' => $country->id,
        'type' => 'billing',
    ]);
});