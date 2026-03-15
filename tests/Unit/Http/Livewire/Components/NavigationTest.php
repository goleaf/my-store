<?php

use App\Livewire\Components\Navigation;
use Livewire\Livewire;
use App\Store\Models\Collection;
use App\Store\Models\Language;

test('component can mount', function () {
    Livewire::test(Navigation::class)
        ->assertViewIs('livewire.components.navigation');
});

test('collections are visible', function () {
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