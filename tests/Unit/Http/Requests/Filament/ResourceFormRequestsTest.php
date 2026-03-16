<?php

use App\Http\Requests\Filament\Sales\CustomerRequest;
use App\Http\Requests\Filament\Shipping\DeliveryZoneRequest;
use App\Http\Requests\Filament\System\CurrencyRequest;
use App\Models\Currency;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

test('currency request keeps code uniqueness and numeric fields in the request', function () {
    $record = makeExistingModel(new Currency, 42);
    $request = (new CurrencyRequest)->forRecord($record);
    $codeRules = collect($request->fieldRules('code'));
    $uniqueRule = $codeRules->first(fn (mixed $rule): bool => $rule instanceof Unique);

    expect($request->fieldRules('name'))->toBe([
        'required',
        'string',
        'max:255',
    ])->and($uniqueRule)->toBeInstanceOf(Unique::class)
        ->and((string) $uniqueRule)->toContain('"42"')
        ->and($request->fieldRules('decimal_places'))->toBe([
            'required',
            'integer',
            'min:0',
        ]);
});

test('delivery zone request owns the numeric money and toggle rules', function () {
    $request = new DeliveryZoneRequest;

    expect($request->fieldRules('name'))->toBe([
        'required',
        'string',
        'max:255',
    ])->and($request->fieldRules('min_order'))->toBe([
        'nullable',
        'numeric',
        'min:0',
    ])->and($request->fieldRules('is_active'))->toBe([
        'required',
        'boolean',
    ]);
});

test('customer request keeps email uniqueness and status rules in the request', function () {
    $record = makeExistingModel(new Customer, 42);
    $request = (new CustomerRequest)->forRecord($record);
    $emailRules = collect($request->fieldRules('email'));
    $uniqueRule = $emailRules->first(fn (mixed $rule): bool => $rule instanceof Unique);

    expect($request->fieldRules('title'))->toBe([
        'required',
        'string',
        'max:255',
    ])->and($uniqueRule)->toBeInstanceOf(Unique::class)
        ->and((string) $uniqueRule)->toContain('"42"')
        ->and($request->fieldRules('status'))->toContain('required')
        ->and($request->fieldRules('customerGroups'))->toBe([
            'nullable',
            'array',
        ]);
});

function makeExistingModel(Model $model, int $id): Model
{
    $model->forceFill(['id' => $id]);
    $model->exists = true;
    $model->syncOriginal();

    return $model;
}
