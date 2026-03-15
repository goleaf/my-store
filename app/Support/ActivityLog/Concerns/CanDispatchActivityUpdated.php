<?php

namespace App\Support\ActivityLog\Concerns;

use App\Livewire\Components\ActivityLogFeed;

trait CanDispatchActivityUpdated
{
    protected function dispatchActivityUpdated(): bool
    {
        $this->dispatch(ActivityLogFeed::UPDATED)->to(ActivityLogFeed::class);

        return true;
    }
}
