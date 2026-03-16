<?php

use App\Models\Customer;
use Illuminate\Foundation\Auth;

test('customer extends authenticatable', function () {
    expect(Customer::class)->toExtend(Auth\User::class);
});

test('customer has fillable name email password', function () {
    $customer = new Customer;

    expect($customer->getFillable())->toContain('name', 'email', 'password');
});

test('customer can be created with factory', function () {
    $customer = Customer::factory()->create([
        'first_name' => 'Test',
        'last_name' => 'Customer',
        'email' => 'test@example.com',
    ]);

    expect($customer->name)->toBe('Test Customer')
        ->and($customer->email)->toBe('test@example.com');
});
