<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Channel;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelBlink\BlinkFacade as Blink;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    Blink::flush();
    if (Channel::count() === 0) {
        Channel::factory()->create(['default' => true]);
    }
    if (Currency::count() === 0) {
        Currency::factory()->create([
            'default' => true,
            'code' => 'USD',
            'exchange_rate' => 1,
            'decimal_places' => 2,
            'enabled' => true,
        ]);
        // Also create GBP since the Order factory uses it
        Currency::factory()->create([
            'default' => false,
            'code' => 'GBP',
            'exchange_rate' => 0.8,
            'decimal_places' => 2,
            'enabled' => true,
        ]);
    }
    Blink::flush();
});

test('account pages require authentication', function (string $route) {
    get(route($route))->assertRedirect(route('login'));
})->with([
    'account.settings',
    'account.orders',
    'account.addresses',
    'account.payment-methods',
    'account.notifications',
]);

test('authenticated user can access account settings', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('account.settings'))
        ->assertOk()
        ->assertSeeLivewire('account.settings');
});

test('a user can update their profile', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    // Create a customer for the user to test phone update
    $customer = Customer::factory()->create();
    $user->customers()->attach($customer);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\Settings::class)
        ->set('name', 'New Name')
        ->set('email', 'new@example.com')
        ->set('phone', '1234567890')
        ->call('updateProfile')
        ->assertHasNoErrors()
        ->assertSee('Profile updated successfully.');

    $user->refresh();
    expect($user->name)->toBe('New Name')
        ->and($user->email)->toBe('new@example.com');

    $customer->refresh();
    expect($customer->meta['phone'])->toBe('1234567890');
});

test('a user can update their password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\Settings::class)
        ->set('current_password', 'old-password')
        ->set('new_password', 'new-password-123')
        ->set('new_password_confirmation', 'new-password-123')
        ->call('updatePassword')
        ->assertHasNoErrors()
        ->assertSee('Password updated successfully.');

    $user->refresh();
    expect(Hash::check('new-password-123', $user->password))->toBeTrue();
});

test('a user can manage addresses', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();
    $user->customers()->attach($customer);

    $country = Country::factory()->create(['iso2' => 'US']);

    // Test Create
    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\Addresses::class)
        ->set('address.first_name', 'John')
        ->set('address.last_name', 'Doe')
        ->set('address.line_one', '123 Main St')
        ->set('address.city', 'New York')
        ->set('address.postcode', '10001')
        ->set('address.country_id', $country->id)
        ->call('saveAddress')
        ->assertHasNoErrors()
        ->assertSee('Address created successfully.');

    $this->assertDatabaseHas(\App\Models\Address::class, [
        'customer_id' => $customer->id,
        'first_name' => 'John',
        'line_one' => '123 Main St',
    ]);

    $address = Address::where('customer_id', $customer->id)->first();

    // Test Edit
    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\Addresses::class)
        ->call('editAddress', $address->id)
        ->assertSet('address.first_name', 'John')
        ->set('address.first_name', 'Jane')
        ->call('saveAddress')
        ->assertHasNoErrors()
        ->assertSee('Address updated successfully.');

    expect($address->refresh()->first_name)->toBe('Jane');

    // Test Delete
    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\Addresses::class)
        ->call('deleteAddress', $address->id)
        ->assertSee('Address deleted successfully.');

    $this->assertDatabaseMissing(\App\Models\Address::class, ['id' => $address->id]);
});

test('a user can manage payment methods', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['meta' => []]);
    $user->customers()->attach($customer);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\PaymentMethods::class)
        ->set('newCard.name', 'John Doe')
        ->set('newCard.number', '4242424242424242')
        ->set('newCard.expiry', '12/25')
        ->set('newCard.cvv', '123')
        ->call('addCard')
        ->assertHasNoErrors()
        ->assertSee('Payment method added successfully.');

    $customer->refresh();
    $paymentMethods = $customer->meta['payment_methods'];
    expect($paymentMethods)->toHaveCount(1);
    expect($paymentMethods[0]['last4'])->toBe('4242');

    $cardId = $paymentMethods[0]['id'];

    // Test Delete
    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\PaymentMethods::class)
        ->call('deleteCard', $cardId)
        ->assertSee('Payment method deleted successfully.');

    $customer->refresh();
    expect($customer->meta['payment_methods'] ?? [])->toHaveCount(0);
});

test('a user can update notification preferences', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['meta' => []]);
    $user->customers()->attach($customer);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Account\Notifications::class)
        ->set('preferences.promotions', true)
        ->call('updatePreferences')
        ->assertHasNoErrors()
        ->assertSee('Notification preferences updated successfully.');

    $customer->refresh();
    expect($customer->meta['notification_preferences']['promotions'])->toBeTrue();
});

test('a user can see their orders', function () {
    $user = User::factory()->create();
    $orders = Order::factory()->count(3)->create(['user_id' => $user->id]);
    actingAs($user)
        ->get(route('account.orders'))
        ->assertOk()
        ->assertSeeLivewire('account.orders');
});

test('a user can see order details', function () {
    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->get(route('account.orders.view', $order->id))
        ->assertOk()
        ->assertSeeLivewire('account.order-details')
        ->assertSee($order->reference);
});

test('a user cannot see someone elses order details', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $otherUser->id]);

    actingAs($user)
        ->get(route('account.orders.view', $order->id))
        ->assertForbidden();
});
