<?php

namespace App\Support\ActivityLog\Orders;

use App\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class EmailNotification extends AbstractRender
{
    public function getEvent(): string
    {
        return 'email-notification';
    }

    public function render(Activity $log)
    {
        return view('admin::partials.orders.activity.email-notification', [
            'log' => $log,
        ]);
    }
}
