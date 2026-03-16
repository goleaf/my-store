<?php

use App\Models\Attribute;
use App\Models\Channel;
use App\Models\CollectionGroup;
use App\Models\Country;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\DeliverySlot;
use App\Models\FeaturedCategory;
use App\Models\HomeHero;
use App\Models\HomeBanner;
use App\Models\HomeSection;
use App\Models\Language;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\ProductReview;
use App\Models\PromoBlock;
use App\Models\SavedPaymentMethod;
use App\Models\SiteSetting;
use App\Models\Store;
use App\Models\Store\Models\Announcement;
use App\Models\Store\Models\DeliveryZone;
use App\Models\Wishlist;
use App\Models\ProductType;
use App\Models\TaxClass;
use App\Models\TaxZone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

test('database seeder completes with required countries', function () {
    Artisan::call('db:seed');

    expect(Country::where('iso3', 'GBR')->exists())->toBeTrue();
    expect(Country::where('iso3', 'USA')->exists())->toBeTrue();
    expect(Channel::whereDefault(true)->exists())->toBeTrue();
    expect(Language::whereDefault(true)->exists())->toBeTrue();
    expect(Currency::whereDefault(true)->exists())->toBeTrue();
    expect(CustomerGroup::whereDefault(true)->exists())->toBeTrue();
    expect(CollectionGroup::count())->toBeGreaterThan(0);
    expect(TaxClass::whereDefault(true)->exists())->toBeTrue();
    expect(TaxZone::whereDefault(true)->exists())->toBeTrue();
    expect(ProductType::count())->toBeGreaterThan(0);
    expect(Attribute::where('handle', 'name')->exists())->toBeTrue();
    expect(HomeHero::query()->count())->toBeGreaterThan(0);
    expect(HomeHero::query()->ordered()->first()?->translate('title'))->not->toBeEmpty();
    expect(Announcement::query()->count())->toBeGreaterThan(0);
    expect(DeliveryZone::query()->count())->toBeGreaterThan(0);
    expect(DeliverySlot::query()->count())->toBeGreaterThan(0);
    expect(FeaturedCategory::query()->count())->toBeGreaterThan(0);
    expect(FeaturedCategory::query()->ordered()->first()?->title)->not->toBeEmpty();
    expect(HomeBanner::query()->count())->toBeGreaterThan(0);
    expect(HomeSection::query()->count())->toBeGreaterThan(0);
    expect(PromoBlock::query()->count())->toBeGreaterThan(0);
    expect(SiteSetting::query()->count())->toBeGreaterThan(0);
    expect(Store::query()->count())->toBeGreaterThan(0);
    expect(PostCategory::query()->count())->toBeGreaterThan(0);
    expect(Post::query()->count())->toBeGreaterThan(0);
    expect(SavedPaymentMethod::query()->count())->toBeGreaterThan(0);
    expect(Wishlist::query()->count())->toBeGreaterThan(0);
    expect(ProductReview::query()->count())->toBeGreaterThan(0);
});
