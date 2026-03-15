<?php

test('prices_inc_tax helper exists', function () {
    expect(function_exists('prices_inc_tax'))->toBeTrue();
    $result = prices_inc_tax();
    expect($result)->toBeBool();
});

test('can_drop_foreign_keys helper exists', function () {
    expect(function_exists('can_drop_foreign_keys'))->toBeTrue();
    $result = can_drop_foreign_keys();
    expect($result)->toBeBool();
});
