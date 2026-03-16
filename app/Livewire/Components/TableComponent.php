<?php

namespace App\Livewire\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Filament\Tables;

class TableComponent extends Tables\TableComponent implements HasForms
{
    use InteractsWithForms;

    #[Locked]
    public Model $record;

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->table }}
        </div>
        HTML;
    }
}
