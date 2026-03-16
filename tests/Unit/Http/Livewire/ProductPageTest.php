<?php

use App\Livewire\ProductPage;
use App\Models\Brand;
use App\Models\Collection;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductAssociation;
use App\Models\ProductVariant;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Illuminate\Database\Eloquent\Factories\Factory;

function createRenderableProductPageProduct(Brand|Factory|null $brand = null): Product
{
    Language::query()->firstWhere('default', true)
        ?? Language::factory()->create([
            'default' => true,
        ]);

    $currency = Currency::query()->firstWhere('default', true)
        ?? Currency::factory()->create([
            'default' => true,
        ]);

    $product = Product::factory()
        ->for($brand ?? Brand::factory(), 'brand')
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

    return $product;
}

test('component can mount', function () {
    $product = createRenderableProductPageProduct();

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertViewIs('livewire.product-page');
});

test('correct product is loaded', function () {
    $product = createRenderableProductPageProduct();

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertViewIs('livewire.product-page')
        ->assertSet('product.id', $product->id);
});

test('product is visible', function () {
    $product = createRenderableProductPageProduct();

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertViewIs('livewire.product-page')
        ->assertSee($product->translateAttribute('name'));
});

test('brand link is rendered from controller-prepared data', function () {
    $product = createRenderableProductPageProduct(
        Brand::factory()->hasUrls(1, [
            'default' => true,
        ])->state([
            'name' => 'Linked Brand',
        ])
    );

    $brand = $product->brand->load('defaultUrl');

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertSee('Linked Brand')
        ->assertSee(route('brand.view', $brand->defaultUrl->slug));
});

test('brand name falls back to plain text when no brand url exists', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $brand = Brand::factory()->create([
        'name' => 'Plain Brand',
    ]);

    $brand->urls()->delete();

    $product = createRenderableProductPageProduct($brand);

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertSee('Plain Brand')
        ->assertDontSee('/brands/', false);
});

test('collection links are rendered from controller-prepared data', function () {
    $product = createRenderableProductPageProduct();

    $collection = Collection::factory()
        ->hasUrls(1, [
            'default' => true,
        ])
        ->create();

    $product->collections()->attach($collection);

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertSee($collection->translateAttribute('name'))
        ->assertSee(route('collection.view', $collection->defaultUrl->slug));
});

test('related product links are rendered from eager-loaded association data', function () {
    $product = createRenderableProductPageProduct();
    $target = createRenderableProductPageProduct();

    ProductAssociation::factory()->create([
        'product_parent_id' => $product->id,
        'product_target_id' => $target->id,
    ]);

    Livewire::test(ProductPage::class, ['slug' => $product->defaultUrl->slug])
        ->assertSee($target->translateAttribute('name'))
        ->assertSee(route('product.view', $target->defaultUrl->slug));
});
