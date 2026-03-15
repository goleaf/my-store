<?php

namespace App\Admin\Support\ActivityLog\Orders;

use App\Admin\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class Capture extends AbstractRender
{
    public function getEvent(): string
    {
        return 'capture';
    }

    public function render(Activity $log)
    {
        return view('admin::partials.orders.activity.capture', [
            'log' => $log,
        ]);
    }
}
