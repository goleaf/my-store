<?php

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController;

test('controller extends base controller', function () {
    expect(Controller::class)->toExtend(BaseController::class);
});
