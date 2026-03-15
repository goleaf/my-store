<?php

use App\Traits\FetchesUrls;
use App\Store\Models\Url;
use App\Store\Models\Product;
use App\Store\Models\Language;

test('fetchUrl returns null when no url exists', function () {
    $trait = new class {
        use FetchesUrls;
    };

    $result = $trait->fetchUrl('non-existent-slug', (new Product)->getMorphClass(), []);

    expect($result)->toBeNull();
});

test('fetchUrl returns url when slug exists', function () {
    Language::factory()->create(['default' => true]);
    $product = Product::factory()->hasUrls(1, [
        'default' => true,
    ])->create();
    $slug = $product->defaultUrl->slug;

    $trait = new class {
        use FetchesUrls;
    };

    $result = $trait->fetchUrl($slug, $product->getMorphClass(), []);

    expect($result)->toBeInstanceOf(Url::class)
        ->and($result->slug)->toBe($slug)
        ->and($result->element_id)->toBe($product->id);
});
