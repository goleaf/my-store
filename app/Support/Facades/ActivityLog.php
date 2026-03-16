<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Support\ActivityLog\Manifest addRender(string $subject, string $renderer)
 * @method static \Illuminate\Support\Collection getItems(string $subject)
 *
 * @see \App\Support\ActivityLog\Manifest
 */
class ActivityLog extends Facade
{
    /**
     * Return the facade class reference.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'admin-activity-log';
    }
}
