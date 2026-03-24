<?php

use App\FieldTypes\Text;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Url;

test('product page shows the product name', function () {
    Language::factory()->create([
        'code' => 'en',
        'default' => true,
    ]);

    $product = Product::factory()->create([
        'attribute_data' => collect([
            'name' => new Text('Test Product'),
            'description' => new Text('Test description'),
        ]),
    ]);

    $variant = ProductVariant::factory()
        ->for($product)
        ->create();

    $currency = Currency::factory()->create();

    Price::factory()
        ->for($variant, 'priceable')
        ->create([
            'currency_id' => $currency->id,
            'min_quantity' => 1,
            'customer_group_id' => null,
        ]);

    $url = Url::factory()->create([
        'element_type' => $product->getMorphClass(),
        'element_id' => $product->id,
        'slug' => 'test-product',
        'default' => true,
    ]);

    $this->get(route('product.view', ['slug' => $url->slug]))
        ->assertSuccessful()
        ->assertSee('Test Product');
});

test('product page returns not found when product has no variants', function () {
    Language::factory()->create([
        'code' => 'en',
        'default' => true,
    ]);

    $product = Product::factory()->create([
        'attribute_data' => collect([
            'name' => new Text('Variantless Product'),
            'description' => new Text('No variants attached'),
        ]),
    ]);

    $url = Url::factory()->create([
        'element_type' => $product->getMorphClass(),
        'element_id' => $product->id,
        'slug' => 'variantless-product',
        'default' => true,
    ]);

    $this->get(route('product.view', ['slug' => $url->slug]))
        ->assertNotFound();
});
