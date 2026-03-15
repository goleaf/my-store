<?php

use App\Store\Models\Attribute;
use App\Store\Models\Channel;
use App\Store\Models\CollectionGroup;
use App\Store\Models\Country;
use App\Store\Models\Currency;
use App\Store\Models\CustomerGroup;
use App\Store\Models\Language;
use App\Store\Models\ProductType;
use App\Store\Models\TaxClass;
use App\Store\Models\TaxZone;
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
});
