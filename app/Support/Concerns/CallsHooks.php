<?php

namespace App\Support\Concerns;

use App\Support\Facades\AdminPanel;

trait CallsHooks
{
    protected function callStoreHook(...$args)
    {
        return AdminPanel::callHook(static::class, $this, ...$args);
    }

    protected static function callStaticStoreHook(...$args)
    {
        return AdminPanel::callHook(static::class, null, ...$args);
    }
}
