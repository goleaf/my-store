<?php

namespace Tests\Feature;

use App\Base\Enums\SavedPaymentMethodType;
use App\Models\Address;
use App\Models\Channel;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Order;
use App\Models\SavedPaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelBlink\BlinkFacade;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use App\Livewire\Account\Addresses;
use App\Livewire\Account\Notifications;
use App\Livewire\Account\PaymentMethods;
use App\Livewire\Account\Settings;

uses(RefreshDatabase::class);

beforeEach(function () {
    BlinkFacade::flush();
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

    if (Country::count() === 0) {
        Country::factory()->create([
            'name' => 'United States',
            'iso3' => 'USA',
            'iso2' => 'US',
        ]);
    }

    BlinkFacade::flush();
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

test('authenticated customer can access account settings', function () {
    $customer = Customer::factory()->create();

    actingAs($customer)
        ->get(route('account.settings'))
        ->assertOk()
        ->assertSeeLivewire('account.settings');
});

test('a customer can update their profile', function () {
    $customer = Customer::factory()->create([
        'first_name' => 'Old',
        'last_name' => 'Name',
        'email' => 'old@example.com',
        'phone' => null,
    ]);

    Livewire::actingAs($customer)
        ->test(Settings::class)
        ->set('name', 'New Name')
        ->set('email', 'new@example.com')
        ->set('phone', '1234567890')
        ->call('updateProfile')
        ->assertHasNoErrors()
        ->assertSee('Profile updated successfully.');

    $customer->refresh();
    expect($customer->first_name)->toBe('New')
        ->and($customer->last_name)->toBe('Name')
        ->and($customer->email)->toBe('new@example.com')
        ->and($customer->phone)->toBe('1234567890');
});

test('a customer can update their password', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    Livewire::actingAs($customer)
        ->test(Settings::class)
        ->set('current_password', 'old-password')
        ->set('new_password', 'new-password-123')
        ->set('new_password_confirmation', 'new-password-123')
        ->call('updatePassword')
        ->assertHasNoErrors()
        ->assertSee('Password updated successfully.');

    $customer->refresh();
    expect(Hash::check('new-password-123', $customer->password))->toBeTrue();
});

test('a customer can manage addresses', function () {
    $country = Country::query()->firstOrFail();
    $customer = Customer::factory()->create();

    // Test Create
    Livewire::actingAs($customer)
        ->test(Addresses::class)
        ->set('address.title', 'Home')
        ->set('address.first_name', 'John')
        ->set('address.last_name', 'Doe')
        ->set('address.contact_phone', '1234567890')
        ->set('address.line_one', '123 Main St')
        ->set('address.city', 'New York')
        ->set('address.state', 'NY')
        ->set('address.postcode', '10001')
        ->set('address.country_id', $country->id)
        ->set('address.shipping_default', true)
        ->call('saveAddress')
        ->assertHasNoErrors()
        ->assertSee('Address created successfully.');

    $this->assertDatabaseHas(Address::class, [
        'customer_id' => $customer->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'line_one' => '123 Main St',
        'shipping_default' => true,
    ]);

    $address = Address::query()->where('customer_id', $customer->id)->firstOrFail();

    // Test Edit
    Livewire::actingAs($customer)
        ->test(Addresses::class)
        ->call('editAddress', $address->id)
        ->assertSet('address.first_name', 'John')
        ->set('address.first_name', 'Jane')
        ->call('saveAddress')
        ->assertHasNoErrors()
        ->assertSee('Address updated successfully.');

    expect($address->refresh()->first_name)->toBe('Jane');

    // Test Delete
    Livewire::actingAs($customer)
        ->test(Addresses::class)
        ->call('deleteAddress', $address->id)
        ->assertSee('Address deleted successfully.');

    $this->assertDatabaseMissing(Address::class, ['id' => $address->id]);
});

test('a customer can manage payment methods', function () {
    $customer = Customer::factory()->create();

    Livewire::actingAs($customer)
        ->test(PaymentMethods::class)
        ->set('method.type', SavedPaymentMethodType::Card->value)
        ->set('method.cardholder_name', 'John Doe')
        ->set('method.card_number', '4242424242424242')
        ->set('method.expiry_month', 12)
        ->set('method.expiry_year', now()->year + 1)
        ->set('method.is_default', true)
        ->call('addMethod')
        ->assertHasNoErrors()
        ->assertSee('Payment method added successfully.');

    $paymentMethod = SavedPaymentMethod::where('customer_id', $customer->id)->firstOrFail();

    expect($paymentMethod->type)->toBe(SavedPaymentMethodType::Card)
        ->and($paymentMethod->last_four)->toBe('4242')
        ->and($paymentMethod->brand)->toBe('Visa')
        ->and($paymentMethod->is_default)->toBeTrue();

    // Test Delete
    Livewire::actingAs($customer)
        ->test(PaymentMethods::class)
        ->call('deleteMethod', $paymentMethod->id)
        ->assertSee('Payment method deleted successfully.');

    $this->assertDatabaseMissing(SavedPaymentMethod::class, ['id' => $paymentMethod->id]);
});

test('a customer can add each saved payment method type', function (SavedPaymentMethodType $type, array $fields, array $expectations) {
    $customer = Customer::factory()->create();

    $component = Livewire::actingAs($customer)
        ->test(PaymentMethods::class)
        ->set('method.type', $type->value)
        ->set('method.is_default', false);

    foreach ($fields as $field => $value) {
        $component->set("method.{$field}", $value);
    }

    $component
        ->call('addMethod')
        ->assertHasNoErrors()
        ->assertSee('Payment method added successfully.');

    $paymentMethod = SavedPaymentMethod::where('customer_id', $customer->id)->sole();

    expect($paymentMethod->type)->toBe($type)
        ->and($paymentMethod->is_default)->toBeTrue();

    foreach ($expectations as $attribute => $value) {
        expect($paymentMethod->{$attribute})->toBe($value);
    }
})->with([
    'card' => [
        SavedPaymentMethodType::Card,
        [
            'cardholder_name' => 'John Doe',
            'card_number' => '4242424242424242',
            'expiry_month' => 12,
            'expiry_year' => now()->year + 1,
        ],
        [
            'brand' => 'Visa',
            'last_four' => '4242',
            'expiry_month' => 12,
            'expiry_year' => now()->year + 1,
        ],
    ],
    'paypal' => [
        SavedPaymentMethodType::Paypal,
        [
            'paypal_email' => 'paypal@example.com',
        ],
        [
            'paypal_email' => 'paypal@example.com',
        ],
    ],
    'payoneer' => [
        SavedPaymentMethodType::Payoneer,
        [
            'payoneer_account_id' => 'payoneer-account-1',
        ],
        [
            'payoneer_account_id' => 'payoneer-account-1',
        ],
    ],
]);

test('a customer can update notification preferences', function () {
    $customer = Customer::factory()->create(['meta' => []]);

    Livewire::actingAs($customer)
        ->test(Notifications::class)
        ->set('preferences.promotions', true)
        ->call('updatePreferences')
        ->assertHasNoErrors()
        ->assertSee('Notification preferences updated successfully.');

    $customer->refresh();
    expect($customer->meta['notification_preferences']['promotions'])->toBeTrue();
});

test('a customer can see their orders', function () {
    $customer = Customer::factory()->create();
    Order::factory()->count(3)->create(['customer_id' => $customer->id]);
    actingAs($customer)
        ->get(route('account.orders'))
        ->assertOk()
        ->assertSeeLivewire('account.orders');
});

test('a customer can see order details', function () {
    $customer = Customer::factory()->create();
    $order = Order::factory()->create(['customer_id' => $customer->id]);

    actingAs($customer)
        ->get(route('account.orders.view', $order->id))
        ->assertOk()
        ->assertSeeLivewire('account.order-details')
        ->assertSee($order->reference);
});

test('a customer cannot see someone elses order details', function () {
    $customer = Customer::factory()->create();
    $otherCustomer = Customer::factory()->create();
    $order = Order::factory()->create(['customer_id' => $otherCustomer->id]);

    actingAs($customer)
        ->get(route('account.orders.view', $order->id))
        ->assertForbidden();
});
