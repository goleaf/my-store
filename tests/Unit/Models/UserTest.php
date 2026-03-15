<?php

use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;

test('user extends authenticatable', function () {
    expect(User::class)->toExtend(Authenticatable::class);
});

test('user has fillable name email password', function () {
    $user = new User;

    expect($user->getFillable())->toContain('name', 'email', 'password');
});

test('user can be created with factory', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com');
});
