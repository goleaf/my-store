<?php

use App\FieldTypes\Text;
use App\Livewire\WishlistPage;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Customer;
use App\Models\Wishlist;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('wishlist page requires authentication', function () {
    get(route('wishlist.view'))->assertRedirect(route('login'));
});

test('authenticated user can view wishlist page', function () {
    $customer = Customer::factory()->create();

    actingAs($customer)
        ->get(route('wishlist.view'))
        ->assertOk()
        ->assertSeeLivewire('wishlist-page');
});

test('user can toggle products in wishlist', function () {
    Language::factory()->create([
        'default' => true,
    ]);

    $currency = Currency::factory()->create([
        'default' => true,
        'enabled' => true,
    ]);

    $product = Product::factory()
        ->state([
            'attribute_data' => collect([
                'name' => new Text('Wishlist Product'),
                'description' => new Text('Wishlist description'),
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

    $customer = Customer::factory()->create();

    Livewire::actingAs($customer)
        ->test(WishlistPage::class)
        ->call('toggleWishlist', $product->id)
        ->assertHasNoErrors();

    expect(Wishlist::query()->where('customer_id', $customer->id)->where('product_id', $product->id)->exists())
        ->toBeTrue();

    Livewire::actingAs($customer)
        ->test(WishlistPage::class)
        ->call('toggleWishlist', $product->id)
        ->assertHasNoErrors();

    expect(Wishlist::query()->where('customer_id', $customer->id)->where('product_id', $product->id)->exists())
        ->toBeFalse();
});
