<?php

use App\Base\Enums\ProductStatus;
use App\FieldTypes\Text;
use App\Livewire\Components\GlobalSearch;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Livewire;

test('search dropdown opens only for at least two characters', function () {
    Livewire::test(GlobalSearch::class)
        ->set('term', 'a')
        ->assertSet('showDropdown', false)
        ->set('term', 'ap')
        ->assertSet('showDropdown', true);
});

test('search redirects to the search page', function () {
    Livewire::test(GlobalSearch::class)
        ->set('term', 'apples')
        ->call('search')
        ->assertRedirect(route('search.view', ['term' => 'apples']));
});

test('results only include published products', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $currency = Currency::factory()->create([
        'default' => true,
        'enabled' => true,
    ]);

    $publishedProduct = Product::factory()
        ->state([
            'status' => ProductStatus::Published->value,
            'attribute_data' => collect([
                'name' => new Text('Apple Juice'),
                'description' => new Text('Fresh apple juice'),
            ]),
        ])
        ->hasUrls(1, ['default' => true])
        ->has(ProductVariant::factory()->afterCreating(function ($variant) use ($currency) {
            $variant->prices()->create(
                Price::factory()->make([
                    'currency_id' => $currency->id,
                ])->getAttributes()
            );
        }), 'variants')
        ->create();

    Product::factory()
        ->state([
            'status' => ProductStatus::Draft->value,
            'attribute_data' => collect([
                'name' => new Text('Apple Draft'),
                'description' => new Text('Draft apple product'),
            ]),
        ])
        ->has(ProductVariant::factory(), 'variants')
        ->create();

    Livewire::test(GlobalSearch::class)
        ->set('term', 'Apple')
        ->assertSet('showDropdown', true)
        ->assertSee($publishedProduct->translateAttribute('name'))
        ->assertDontSee('Apple Draft');
});
