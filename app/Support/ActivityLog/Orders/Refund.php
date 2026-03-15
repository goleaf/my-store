<?php

namespace App\Support\ActivityLog\Orders;

use App\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class Refund extends AbstractRender
{
    public function getEvent(): string
    {
        return 'refund';
    }

    public function render(Activity $log)
    {
        return view('admin::partials.orders.activity.refund', [
            'log' => $log,
        ]);
    }
}
