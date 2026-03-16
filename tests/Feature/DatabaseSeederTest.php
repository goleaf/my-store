<?php

use App\Models\Attribute;
use App\Models\Channel;
use App\Models\CollectionGroup;
use App\Models\Country;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\Language;
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
});
