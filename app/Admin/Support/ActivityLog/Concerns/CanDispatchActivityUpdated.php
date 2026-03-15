<?php

namespace App\Admin\Support\ActivityLog\Concerns;

use App\Admin\Livewire\Components\ActivityLogFeed;

trait CanDispatchActivityUpdated
{
    protected function dispatchActivityUpdated(): bool
    {
        $this->dispatch(ActivityLogFeed::UPDATED)->to(ActivityLogFeed::class);

        return true;
    }
}
