<?php

use App\Filament\Resources\CurrencyResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\DeliveryZoneResource;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->staff = Staff::factory()->create([
        'admin' => true,
    ]);
});

test('request backed filament resource create pages render successfully', function (string $resourceClass, array $permissions) {
    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'staff');
    }

    if ($permissions !== []) {
        $this->staff->givePermissionTo($permissions);
    }

    $this->actingAs($this->staff, 'staff')
        ->get($resourceClass::getUrl('create'))
        ->assertSuccessful();
})->with([
    [CurrencyResource::class, ['settings:core']],
    [DeliveryZoneResource::class, []],
    [CustomerResource::class, ['sales:manage-customers']],
]);
