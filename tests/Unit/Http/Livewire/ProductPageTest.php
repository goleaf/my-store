<?php

use App\Livewire\ProductPage;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use App\Store\Models\Currency;
use App\Store\Models\Language;
use App\Store\Models\Price;
use App\Store\Models\Product;
use App\Store\Models\ProductVariant;

test('component can mount', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $product = Product::factory()
        ->hasUrls(1, [
            'default' => true,
        ])->has(ProductVariant::factory()->afterCreating(function ($variant) use ($currency) {
            $variant->prices()->create(
                Price::factory()->make([
                    'currency_id' => $currency->id,
                ])->getAttributes()
            );
        }), 'variants')
        ->create();

    $product->addMedia(UploadedFile::fake()->image('product.jpg'))->toMediaCollection('images');

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertViewIs('livewire.product-page');
});

test('correct product is loaded', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $product = Product::factory()
        ->hasUrls(1, [
            'default' => true,
        ])->has(ProductVariant::factory()->afterCreating(function ($variant) use ($currency) {
            $variant->prices()->create(
                Price::factory()->make([
                    'currency_id' => $currency->id,
                ])->getAttributes()
            );
        }), 'variants')
        ->create();

    $product->addMedia(UploadedFile::fake()->image('product.jpg'))->toMediaCollection('images');

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertViewIs('livewire.product-page')
        ->assertSet('product.id', $product->id);
});

test('product is visible', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $product = Product::factory()
        ->hasUrls(1, [
            'default' => true,
        ])->has(ProductVariant::factory()->afterCreating(function ($variant) use ($currency) {
            $variant->prices()->create(
                Price::factory()->make([
                    'currency_id' => $currency->id,
                ])->getAttributes()
            );
        }), 'variants')
        ->create();

    $product->addMedia(UploadedFile::fake()->image('product.jpg'))->toMediaCollection('images');

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertViewIs('livewire.product-page')
        ->assertSee($product->translateAttribute('name'));
});