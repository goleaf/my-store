<?php

use App\Store\FieldTypes\Text;
use App\Store\Models\Currency;
use App\Store\Models\Language;
use App\Store\Models\Price;
use App\Store\Models\Product;
use App\Store\Models\ProductVariant;
use App\Store\Models\Url;

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
