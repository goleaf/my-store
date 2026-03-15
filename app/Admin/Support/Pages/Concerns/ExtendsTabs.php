<?php

namespace App\Admin\Support\Pages\Concerns;

trait ExtendsTabs
{
    protected function getDefaultTabs(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return $this->callStoreHook('getTabs', $this->getDefaultTabs());
    }
}
