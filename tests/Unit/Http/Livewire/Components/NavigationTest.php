<?php

use App\Livewire\Components\Navigation;
use Livewire\Livewire;
use App\Models\Collection;
use App\Models\Language;
use App\Models\Currency;
use App\Models\Channel;
use App\Models\Brand;

test('component can mount', function () {
    Currency::factory()->create([
        'default' => true,
        'enabled' => true,
    ]);
    Channel::factory()->create([
        'default' => true,
    ]);
    Livewire::test(Navigation::class)
        ->assertViewIs('livewire.components.navigation');
});

test('collections are visible', function () {
    Currency::factory()->create([
        'default' => true,
        'enabled' => true,
    ]);
    Channel::factory()->create([
        'default' => true,
    ]);
    Language::factory()->create([
        'default' => true,
    ]);

    $collections = Collection::factory(5)
        ->hasUrls(1, [
            'default' => true,
        ])->create();

    expect($collections)->toHaveCount(5);

    $component = Livewire::test(Navigation::class)
        ->assertViewIs('livewire.components.navigation');

    foreach ($collections as $collection) {
        $component->assertSee($collection->translateAttribute('name'));
    }
});

test('navigation shows working storefront links', function () {
    Currency::factory()->create([
        'default' => true,
        'enabled' => true,
    ]);
    Channel::factory()->create([
        'default' => true,
    ]);
    Language::factory()->create([
        'default' => true,
    ]);

    Brand::factory()
        ->hasUrls(1, ['default' => true])
        ->create();

    Livewire::test(Navigation::class)
        ->assertSee(route('shop.view'))
        ->assertSee(route('search.view'))
        ->assertSee(route('cart.view'));
});
