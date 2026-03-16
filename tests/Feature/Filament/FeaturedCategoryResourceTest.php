<?php

use App\Filament\Resources\FeaturedCategoryResource;
use App\Models\FeaturedCategory;
use App\Models\Staff;
use Database\Seeders\CollectionSeeder;
use Database\Seeders\FeaturedCategorySeeder;
use Database\Seeders\StoreSetupSeeder;

test('featured category seeder populates records for the admin listing', function () {
    $this->seed(StoreSetupSeeder::class);
    $this->seed(CollectionSeeder::class);
    $this->seed(FeaturedCategorySeeder::class);

    $staff = Staff::factory()->create([
        'admin' => true,
    ]);

    $category = FeaturedCategory::query()
        ->with('collection.defaultUrl')
        ->ordered()
        ->first();

    expect(FeaturedCategory::query()->count())->toBeGreaterThan(0);
    expect($category)->not->toBeNull();
    expect($category?->title)->not->toBeEmpty();
    expect($category?->collection?->translateAttribute('name'))->not->toBeEmpty();
    expect($category?->collection?->defaultUrl?->slug)->not->toBeEmpty();

    $this->actingAs($staff, 'staff')
        ->get(FeaturedCategoryResource::getUrl('index'))
        ->assertSuccessful()
        ->assertSee($category->title)
        ->assertSee($category->collection->translateAttribute('name'));
});
