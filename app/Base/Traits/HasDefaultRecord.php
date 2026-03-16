<?php

namespace App\Base\Traits;

use Illuminate\Support\Str;
use Spatie\LaravelBlink\BlinkFacade;

trait HasDefaultRecord
{
    /**
     * Return the default scope.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeDefault($query, $default = true)
    {
        $query->whereDefault($default);
    }

    /**
     * Get the default record.
     *
     * @return self
     */
    public static function getDefault()
    {
        $key = 'store_default_'.Str::snake(self::class);

        return BlinkFacade::once($key, function () {
            return self::query()->default(true)->first();
        });
    }
}
