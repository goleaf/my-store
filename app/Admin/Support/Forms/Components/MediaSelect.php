<?php

namespace App\Admin\Support\Forms\Components;

use Filament\Forms\Components\Select;

class MediaSelect extends Select
{
    protected string $view = 'admin::forms.components.media-select';

    protected function setUp(): void
    {
        parent::setUp();
    }
}
