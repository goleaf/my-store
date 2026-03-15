<?php

namespace App\Support\Pages\Concerns;

trait ExtendsFooterWidgets
{
    protected function getDefaultFooterWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return $this->callStoreHook('footerWidgets', $this->getDefaultFooterWidgets());
    }
}
