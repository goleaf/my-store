<?php

use App\Filament\Resources\ContactSubmissionResource;
use App\Filament\Resources\DeliverySlotResource;
use App\Filament\Resources\PostCategoryResource;
use App\Filament\Resources\PostResource;
use App\Filament\Resources\ProductReviewResource;
use App\Filament\Resources\PromoBlockResource;
use App\Filament\Resources\SiteSettingResource;
use App\Filament\Resources\StoreResource;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->staff = Staff::factory()->create([
        'admin' => true,
    ]);
});

test('filament create pages render after schema validation is moved into request files', function (string $resourceClass) {
    $this->actingAs($this->staff, 'staff')
        ->get($resourceClass::getUrl('create'))
        ->assertSuccessful();
})->with([
    ContactSubmissionResource::class,
    DeliverySlotResource::class,
    PostCategoryResource::class,
    PostResource::class,
    ProductReviewResource::class,
    PromoBlockResource::class,
    SiteSettingResource::class,
    StoreResource::class,
]);
