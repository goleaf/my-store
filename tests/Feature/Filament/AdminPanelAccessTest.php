<?php

use App\Models\Staff;
use Filament\Facades\Filament;

test('guests are redirected to the admin login', function () {
    $this->get(Filament::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});

test('staff can access the admin panel', function () {
    $staff = Staff::factory()->create([
        'admin' => true,
    ]);

    $this->actingAs($staff, 'staff')
        ->get(Filament::getUrl())
        ->assertSuccessful();
});
