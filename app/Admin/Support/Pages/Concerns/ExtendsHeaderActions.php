<?php

namespace App\Admin\Support\Pages\Concerns;

trait ExtendsHeaderActions
{
    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return $this->callStoreHook('headerActions', $this->getDefaultHeaderActions());
    }
}
