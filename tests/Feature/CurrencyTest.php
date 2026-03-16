<?php

namespace Tests\Feature;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelBlink\BlinkFacade;

uses(RefreshDatabase::class);

test('can get default currency', function () {
    BlinkFacade::flush();
    $currency = Currency::factory()->create(['default' => true, 'code' => 'USD']);

    $default = Currency::getDefault();

    expect($default)->not->toBeNull();
    expect($default->code)->toBe('USD');
});
