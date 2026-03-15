<?php

namespace App\Admin\Support\ActivityLog\Orders;

use App\Admin\Support\ActivityLog\AbstractRender;
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
