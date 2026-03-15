<?php

namespace App\Admin\Support\ActivityLog\Orders;

use App\Admin\Support\ActivityLog\AbstractRender;
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
