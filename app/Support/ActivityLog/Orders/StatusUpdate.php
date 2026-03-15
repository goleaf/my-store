<?php

namespace App\Support\ActivityLog\Orders;

use App\Support\ActivityLog\AbstractRender;
use App\Support\OrderStatus;
use Spatie\Activitylog\Models\Activity;

class StatusUpdate extends AbstractRender
{
    public function getEvent(): string
    {
        return 'status-update';
    }

    public function render(Activity $log)
    {
        $previousStatus = $log->getExtraProperty('previous');
        $newStatus = $log->getExtraProperty('new');

        return view('admin::partials.orders.activity.status-update', [
            'log' => $log,
            'previousStatus' => $previousStatus,
            'newStatus' => $newStatus,
            'previousStatusColor' => OrderStatus::getColor($previousStatus),
            'previousStatusLabel' => OrderStatus::getLabel($previousStatus),
            'newStatusColor' => OrderStatus::getColor($newStatus),
            'newStatusLabel' => OrderStatus::getLabel($newStatus),
        ]);
    }
}
