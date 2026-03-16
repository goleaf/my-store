<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Store\Admin\Support\ActivityLog\Manifest addRender(string $subject, string $renderer)
 * @method static \Illuminate\Support\Collection getItems(string $subject)
 *
 * @see \App\Store\Admin\Support\ActivityLog\Manifest
 */
class ActivityLog extends Facade
{
    /**
     * Return the facade class reference.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'store-activity-log';
    }
}
