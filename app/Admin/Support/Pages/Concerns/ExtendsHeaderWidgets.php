<?php

namespace App\Admin\Support\Pages\Concerns;

trait ExtendsHeaderWidgets
{
    protected function getDefaultHeaderWidgets(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return $this->callStoreHook('headerWidgets', $this->getDefaultHeaderWidgets());
    }
}
