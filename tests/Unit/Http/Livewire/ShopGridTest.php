<?php

use App\Base\Enums\ProductStatus;
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
use App\Models\Price;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

    $product1 = Product::factory()->create(['status' => ProductStatus::Published->value]);
    $product1->collections()->attach($category1);

    $product2 = Product::factory()->create(['status' => ProductStatus::Published->value]);
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

    $product1 = Product::factory()->create(['brand_id' => $brand1->id, 'status' => ProductStatus::Published->value]);
    $product2 = Product::factory()->create(['brand_id' => $brand2->id, 'status' => ProductStatus::Published->value]);

    Livewire::test(ShopGrid::class)
        ->set('brands', [$brand1->id])
        ->assertViewHas('products', function ($products) use ($product1, $product2) {
            return $products->contains($product1->id) && !$products->contains($product2->id);
        });
});

test('can filter by ratings', function () {
    $product1 = Product::factory()->create(['rating' => 5, 'status' => ProductStatus::Published->value]);
    $product2 = Product::factory()->create(['rating' => 2, 'status' => ProductStatus::Published->value]);

    Livewire::test(ShopGrid::class)
        ->set('ratings', [4])
        ->assertViewHas('products', function ($products) use ($product1, $product2) {
            return $products->contains($product1->id) && !$products->contains($product2->id);
        });
});

test('can sort by price asc', function () {
    $product1 = Product::factory()->create(['status' => ProductStatus::Published->value]);
    $variant1 = ProductVariant::factory()->create(['product_id' => $product1->id]);
    $currency = Currency::whereCode('USD')->first();
    Price::factory()->create([
        'priceable_id' => $variant1->id,
        'priceable_type' => $variant1->getMorphClass(),
        'price' => 1000,
        'currency_id' => $currency->id,
    ]);

    $product2 = Product::factory()->create(['status' => ProductStatus::Published->value]);
    $variant2 = ProductVariant::factory()->create(['product_id' => $product2->id]);
    Price::factory()->create([
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
    $product = Product::factory()->create(['status' => ProductStatus::Published->value]);
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

    Livewire::test(ShopGrid::class)
        ->call('addToCart', $variant->id)
        ->assertDispatched('add-to-cart');

    expect(App\Facades\CartSession::manager()->lines)->toHaveCount(1);
});

test('price sorting keeps each product only once when multiple variants have prices', function () {
    $currency = Currency::whereCode('USD')->firstOrFail();

    $productWithTwoVariants = Product::factory()->create(['status' => ProductStatus::Published->value]);
    $firstVariant = ProductVariant::factory()->create(['product_id' => $productWithTwoVariants->id]);
    $secondVariant = ProductVariant::factory()->create(['product_id' => $productWithTwoVariants->id]);

    Price::factory()->create([
        'priceable_id' => $firstVariant->id,
        'priceable_type' => $firstVariant->getMorphClass(),
        'price' => 300,
        'currency_id' => $currency->id,
    ]);

    Price::factory()->create([
        'priceable_id' => $secondVariant->id,
        'priceable_type' => $secondVariant->getMorphClass(),
        'price' => 700,
        'currency_id' => $currency->id,
    ]);

    $otherProduct = Product::factory()->create(['status' => ProductStatus::Published->value]);
    $otherVariant = ProductVariant::factory()->create(['product_id' => $otherProduct->id]);
    Price::factory()->create([
        'priceable_id' => $otherVariant->id,
        'priceable_type' => $otherVariant->getMorphClass(),
        'price' => 500,
        'currency_id' => $currency->id,
    ]);

    Livewire::test(ShopGrid::class)
        ->set('sort', 'price_asc')
        ->assertViewHas('products', function ($products) use ($productWithTwoVariants, $otherProduct) {
            $ids = $products->getCollection()->pluck('id')->all();
            $counts = array_count_values($ids);

            return ($counts[$productWithTwoVariants->id] ?? 0) === 1
                && $ids[0] === $productWithTwoVariants->id
                && $ids[1] === $otherProduct->id;
        });
});

test('per page is clamped to allowed values', function () {
    Product::factory()->count(35)->create(['status' => ProductStatus::Published->value]);

    Livewire::test(ShopGrid::class)
        ->set('perPage', 999)
        ->assertSet('perPage', 10)
        ->assertViewHas('products', fn ($products) => $products->perPage() === 10);
});
