<?php

namespace App\Support\Pages\Concerns;

trait ExtendsFormActions
{
    protected function getDefaultFormActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            ...$this->callStoreHook('formActions', $this->getDefaultFormActions()),
        ];
    }
}
