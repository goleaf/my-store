<?php

use App\Filament\Resources\HomeHeroResource;
use App\Models\HomeHero;
use App\Models\Staff;
use Database\Seeders\HomeHeroSeeder;
use Database\Seeders\StoreSetupSeeder;

test('home hero seeder populates translated records for the admin listing', function () {
    $this->seed(StoreSetupSeeder::class);
    $this->seed(HomeHeroSeeder::class);

    $staff = Staff::factory()->create([
        'admin' => true,
    ]);

    $hero = HomeHero::query()->ordered()->first();

    expect(HomeHero::query()->count())->toBe(3);
    expect($hero)->not->toBeNull();
    expect($hero?->title['en'])->not->toBeEmpty();
    expect($hero?->subtitle['en'])->not->toBeEmpty();
    expect($hero?->description['en'])->not->toBeEmpty();
    expect($hero?->link['en'])->not->toBeEmpty();
    expect($hero?->button_text['en'])->not->toBeEmpty();

    $this->actingAs($staff, 'staff')
        ->get(HomeHeroResource::getUrl('index'))
        ->assertSuccessful()
        ->assertSee($hero->translate('title'))
        ->assertSee($hero->translate('subtitle'));
});
