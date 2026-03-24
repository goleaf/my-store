<?php

use App\Http\Requests\System\DownloadPdfRequest;
use App\Models\Channel;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\Language;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    Language::factory()->create([
        'code' => 'en',
        'default' => true,
    ]);

    Currency::factory()->create([
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

    Relation::morphMap(['download_pdf_product' => Product::class]);
    View::addNamespace('admin', resource_path('views/admin'));
});

test('download pdf request validates a supported record payload', function () {
    $product = Product::factory()->create();

    $validated = (new DownloadPdfRequest)->validatePayload([
        'record' => $product->getKey(),
        'record_type' => 'download_pdf_product',
        'view' => 'admin::pdf.order',
    ]);

    expect($validated['record'])->toBe($product->getKey())
        ->and($validated['record_type'])->toBe('download_pdf_product')
        ->and($validated['view'])->toBe('admin::pdf.order');
});

test('download pdf request rejects unsupported morph aliases', function () {
    $product = Product::factory()->create();

    expect(fn () => (new DownloadPdfRequest)->validatePayload([
        'record' => $product->getKey(),
        'record_type' => 'unknown_alias',
        'view' => 'admin::pdf.order',
    ]))->toThrow(ValidationException::class);
});

test('download pdf request rejects views outside the admin pdf namespace', function () {
    $product = Product::factory()->create();

    expect(fn () => (new DownloadPdfRequest)->validatePayload([
        'record' => $product->getKey(),
        'record_type' => 'download_pdf_product',
        'view' => 'admin::orders.index',
    ]))->toThrow(ValidationException::class);
});

test('download pdf request rejects records that do not exist for the mapped model', function () {
    Product::factory()->create();
    $missingProductId = (int) Product::query()->max('id') + 1;
    $mappedClass = Relation::getMorphedModel('download_pdf_product');

    expect($mappedClass)->toBe(Product::class)
        ->and(Product::query()->whereKey($missingProductId)->exists())->toBeFalse();

    expect(fn () => (new DownloadPdfRequest)->validatePayload([
        'record' => $missingProductId,
        'record_type' => 'download_pdf_product',
        'view' => 'admin::pdf.order',
    ]))->toThrow(ValidationException::class);
});
