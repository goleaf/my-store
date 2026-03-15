<?php

namespace App\Support\ActivityLog;

use Spatie\Activitylog\Models\Activity;

class Comment extends AbstractRender
{
    public function getEvent(): string
    {
        return 'comment';
    }

    public function render(Activity $log)
    {
        return view('admin::partials.activity-log.comment', [
            'log' => $log,
        ]);
    }
}
