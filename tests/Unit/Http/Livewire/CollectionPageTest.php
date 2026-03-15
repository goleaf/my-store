<?php

use App\Livewire\CollectionPage;
use Livewire\Livewire;
use App\Store\Models\Collection;
use App\Store\Models\Currency;
use App\Store\Models\Language;
use App\Store\Models\Price;
use App\Store\Models\Product;
use App\Store\Models\ProductVariant;

test('component can mount', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $collection = Collection::factory()
        ->hasUrls(1, [
            'default' => true,
        ])->create();

    Livewire::test(CollectionPage::class, ['slug' => $collection->defaultUrl->slug])
        ->assertViewIs('livewire.collection-page');
});

test('404 if not found', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    Collection::factory()
        ->hasUrls(1, [
            'default' => true,
        ])->create();

    Livewire::test(CollectionPage::class, ['slug' => 'foobar'])
        ->assertStatus(404);
});

test('collection is rendered', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $collection = Collection::factory()
        ->hasUrls(1, [
            'default' => true,
        ])->create();

    Livewire::test(CollectionPage::class, ['slug' => $collection->defaultUrl->slug])
        ->assertSee($collection->translateAttribute('name'))
        ->assertViewIs('livewire.collection-page');
});

test('collection renders products', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $collection = Collection::factory()
        ->hasUrls(1, [
            'default' => true,
        ])->has(
            Product::factory(4)
                ->hasUrls(1, [
                    'default' => true,
                ])
                ->has(ProductVariant::factory()->afterCreating(function ($variant) use ($currency) {
                    $variant->prices()->create(
                        Price::factory()->make([
                            'currency_id' => $currency->id,
                        ])->getAttributes()
                    );
                }), 'variants')
        )->create();

    $component = Livewire::test(CollectionPage::class, ['slug' => $collection->defaultUrl->slug])
        ->assertViewIs('livewire.collection-page');

    foreach ($collection->products as $product) {
        $component->assertSee($product->translateAttribute('name'));
    }
});