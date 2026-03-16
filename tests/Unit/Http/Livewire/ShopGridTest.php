<?php

use App\Livewire\ShopGrid;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\Language;
use App\Models\Currency;
use App\Models\Channel;
use App\Models\CustomerGroup;
use Livewire\Livewire;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Language::factory()->create([
        'code' => 'en',
        'default' => true,
    ]);

    $currency = Currency::factory()->create([
        'code' => 'USD',
        'default' => true,
    ]);

    config(['store.pricing.default_currency' => 'USD']);

    Channel::factory()->create([
        'handle' => 'webstore',
        'default' => true,
    ]);

    CustomerGroup::factory()->create([
        'handle' => 'retail',
        'default' => true,
    ]);
});

test('component can mount', function () {
    Livewire::test(ShopGrid::class)
        ->assertViewIs('livewire.shop-grid');
});

test('can filter by categories', function () {
    $category1 = Collection::factory()->create();
    $category2 = Collection::factory()->create();

    $product1 = Product::factory()->create(['status' => 'published']);
    $product1->collections()->attach($category1);

    $product2 = Product::factory()->create(['status' => 'published']);
    $product2->collections()->attach($category2);

    Livewire::test(ShopGrid::class)
        ->set('categories', [$category1->id])
        ->assertViewHas('products', function ($products) use ($product1, $product2) {
            return $products->contains($product1->id) && !$products->contains($product2->id);
        });
});

test('can filter by brands', function () {
    $brand1 = Brand::factory()->create();
    $brand2 = Brand::factory()->create();

    $product1 = Product::factory()->create(['brand_id' => $brand1->id, 'status' => 'published']);
    $product2 = Product::factory()->create(['brand_id' => $brand2->id, 'status' => 'published']);

    Livewire::test(ShopGrid::class)
        ->set('brands', [$brand1->id])
        ->assertViewHas('products', function ($products) use ($product1, $product2) {
            return $products->contains($product1->id) && !$products->contains($product2->id);
        });
});

test('can filter by ratings', function () {
    $product1 = Product::factory()->create(['rating' => 5, 'status' => 'published']);
    $product2 = Product::factory()->create(['rating' => 2, 'status' => 'published']);

    Livewire::test(ShopGrid::class)
        ->set('ratings', [4])
        ->assertViewHas('products', function ($products) use ($product1, $product2) {
            return $products->contains($product1->id) && !$products->contains($product2->id);
        });
});

test('can sort by price asc', function () {
    $product1 = Product::factory()->create(['status' => 'published']);
    $variant1 = \App\Models\ProductVariant::factory()->create(['product_id' => $product1->id]);
    $currency = Currency::whereCode('USD')->first();
    \App\Models\Price::factory()->create([
        'priceable_id' => $variant1->id,
        'priceable_type' => $variant1->getMorphClass(),
        'price' => 1000,
        'currency_id' => $currency->id,
    ]);

    $product2 = Product::factory()->create(['status' => 'published']);
    $variant2 = \App\Models\ProductVariant::factory()->create(['product_id' => $product2->id]);
    \App\Models\Price::factory()->create([
        'priceable_id' => $variant2->id,
        'priceable_type' => $variant2->getMorphClass(),
        'price' => 500,
        'currency_id' => $currency->id,
    ]);

    Livewire::test(ShopGrid::class)
        ->set('sort', 'price_asc')
        ->assertViewHas('products', function ($products) use ($product1, $product2) {
            return $products->first()->id === $product2->id && $products->last()->id === $product1->id;
        });
});

test('can add to cart', function () {
    $product = Product::factory()->create(['status' => 'published']);
    $variant = \App\Models\ProductVariant::factory()->create(['product_id' => $product->id]);

    Livewire::test(ShopGrid::class)
        ->call('addToCart', $variant->id)
        ->assertDispatched('add-to-cart');

    expect(App\Facades\CartSession::manager()->lines)->toHaveCount(1);
});
