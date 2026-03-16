<?php

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;

test('configured field request adds required and custom rules', function () {
    $request = (new ConfiguredFieldRequest)
        ->forField('title')
        ->required()
        ->withRules(['string', 'max:255']);

    expect($request->fieldRules('title'))->toBe([
        'required',
        'string',
        'max:255',
    ])->and($request->fieldHasRule('title', 'required'))->toBeTrue();
});

test('configured field request defaults to nullable when field is optional', function () {
    $request = (new ConfiguredFieldRequest)
        ->forField('subtitle')
        ->withRules('string');

    expect($request->fieldRules('subtitle'))->toBe([
        'nullable',
        'string',
    ]);
});
