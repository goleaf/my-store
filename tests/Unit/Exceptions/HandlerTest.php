<?php

use App\Exceptions\Handler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

test('handler extends Laravel exception handler', function () {
    expect(Handler::class)->toExtend(ExceptionHandler::class);
});
