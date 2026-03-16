<?php

use App\Http\Controllers\Controller;
use Illuminate\Routing;

test('controller extends base controller', function () {
    expect(Controller::class)->toExtend(Routing\Controller::class);
});
