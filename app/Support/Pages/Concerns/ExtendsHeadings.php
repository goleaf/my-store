<?php

namespace App\Support\Pages\Concerns;

use Illuminate\Contracts\Support\Htmlable;

trait ExtendsHeadings
{
    public function getDefaultHeading(): string
    {
        return $this->heading ?? $this->getTitle();
    }

    public function getHeading(): string|Htmlable
    {
        return $this->callStoreHook('heading', $this->getDefaultHeading(), $this->record ?? null);
    }

    public function getDefaultSubheading(): ?string
    {
        return $this->subheading;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->callStoreHook('subHeading', $this->getDefaultSubheading(), $this->record ?? null);
    }
}
