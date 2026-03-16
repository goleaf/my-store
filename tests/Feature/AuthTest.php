<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

test('login page is accessible', function () {
    get(route('login'))
        ->assertOk()
        ->assertSeeLivewire('auth.login');
});

test('register page is accessible', function () {
    get(route('register'))
        ->assertOk()
        ->assertSeeLivewire('auth.register');
});

test('forgot password page is accessible', function () {
    get(route('password.request'))
        ->assertOk()
        ->assertSeeLivewire('auth.forgot-password');
});

test('a customer can login', function () {
    $customer = Customer::factory()->create([
        'password' => bcrypt($password = 'password123'),
    ]);

    Livewire::test(Login::class)
        ->set('email', $customer->email)
        ->set('password', $password)
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($customer);
});

test('a customer can register', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test Customer')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertDatabaseHas(Customer::class, [
        'email' => 'test@example.com',
        'first_name' => 'Test',
        'last_name' => 'Customer',
    ]);

    $this->assertAuthenticated();
});

test('a customer can logout', function () {
    $customer = Customer::factory()->create();
    $this->actingAs($customer);

    post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});
