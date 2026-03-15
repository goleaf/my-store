<?php

namespace App\Support\ActivityLog\Orders;

use App\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class Intent extends AbstractRender
{
    public function getEvent(): string
    {
        return 'intent';
    }

    public function render(Activity $log)
    {
        return view('admin::partials.orders.activity.intent', [
            'log' => $log,
        ]);
    }
}
