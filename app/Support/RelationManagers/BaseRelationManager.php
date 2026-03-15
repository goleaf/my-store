<?php

namespace App\Support\RelationManagers;

use App\Admin\Support\Concerns;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\On;

#[On('refresh-relation-manager')]
class BaseRelationManager extends RelationManager
{
    use \App\Support\Concerns\CallsHooks;
    use \App\Support\Concerns\RelationManagers\ExtendsForms;
    use \App\Support\Concerns\RelationManagers\ExtendsTables;

    protected function getForms(): array
    {
        $forms = parent::getForms();

        if (App::runningUnitTests() && ! in_array('form', $forms)) {
            // initialize the form when running tests, so we can run assertions on it
            $forms[] = 'form';
        }

        return $forms;
    }
}
