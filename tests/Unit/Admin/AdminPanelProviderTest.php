<?php

use App\Filament\AdminPanelProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->app->register(App\Store\StoreServiceProvider::class);
    $this->app->register(AdminPanelProvider::class);
});

test('admin panel is registered', function () {
    expect(app('admin-panel'))->toBeObject();
});

test('admin panel config is merged', function () {
    expect(config('store.panel'))->toBeArray();
});
