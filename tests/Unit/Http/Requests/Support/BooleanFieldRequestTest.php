<?php

use App\Http\Requests\Support\BooleanFieldRequest;

test('boolean field request returns a boolean rule for the configured field', function () {
    $request = (new BooleanFieldRequest)->forField('is_active');

    expect($request->rules())->toBe([
        'is_active' => ['nullable', 'boolean'],
    ])->and($request->fieldRules('is_active'))->toBe(['nullable', 'boolean']);
});
