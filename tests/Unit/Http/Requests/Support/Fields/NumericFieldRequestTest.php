<?php

use App\Http\Requests\Support\Fields\NumericFieldRequest;

test('numeric field request includes numeric min and max rules', function () {
    $request = (new NumericFieldRequest)
        ->forField('rating')
        ->required()
        ->min(0)
        ->max(5);

    expect($request->fieldRules('rating'))->toBe([
        'required',
        'numeric',
        'min:0',
        'max:5',
    ])->and($request->fieldHasRule('rating', 'numeric'))->toBeTrue();
});
