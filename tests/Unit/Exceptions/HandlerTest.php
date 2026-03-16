<?php

use App\Exceptions\Handler;
use Illuminate\Foundation\Exceptions;

test('handler extends Laravel exception handler', function () {
    expect(Handler::class)->toExtend(Exceptions\Handler::class);
});
