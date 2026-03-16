<?php

namespace App\Support\Infolists\Components;

use Closure;
use Filament\Infolists\Components\Entry;

class Livewire extends Entry
{
    protected string $view = 'admin::infolists.components.livewire';

    protected string $livewireComponent;

    protected ?Closure $configureComponentUsing = null;

    public function content(string $livewireComponent): static
    {
        $this->livewireComponent = $livewireComponent;

        return $this;
    }

    public function getContent(): string
    {
        return $this->livewireComponent;
    }

    public function getContentName(): string
    {
        return str($this->livewireComponent)
            ->replace('\\', '.')
            ->toString();
    }
}
