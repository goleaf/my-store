<?php

use App\Models\User;
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

test('a user can login', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'password123'),
    ]);

    Livewire::test(\App\Livewire\Auth\Login::class)
        ->set('email', $user->email)
        ->set('password', $password)
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

test('a user can register', function () {
    Livewire::test(\App\Livewire\Auth\Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);

    $this->assertDatabaseHas(\App\Models\Customer::class, [
        'first_name' => 'Test',
        'last_name' => 'User',
    ]);

    $this->assertAuthenticated();
});

test('a user can logout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});
