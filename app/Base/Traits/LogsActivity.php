<?php

namespace App\Base\Traits;

use Illuminate\Support\Arr;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits;

trait LogsActivity
{
    use Traits\LogsActivity;

    public static array $logExcept = [];

    /**
     * Get the log options for the activity log.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('store')
            ->logAll()
            ->logExcept(array_merge(['updated_at'], static::getActivitylogExcept()))
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public static function addActivitylogExcept(array|string $fields)
    {
        $fields = Arr::wrap($fields);

        static::$logExcept = array_merge(static::$logExcept, $fields);
    }

    public static function getDefaultLogExcept(): array
    {
        return [];
    }

    public static function getActivitylogExcept(): array
    {
        return array_merge(static::getDefaultLogExcept(), static::$logExcept);
    }
}
