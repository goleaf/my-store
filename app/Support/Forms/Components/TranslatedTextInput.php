<?php

namespace App\Support\Forms\Components;

use Filament\Forms\Components\TextInput;

class TranslatedTextInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();
    }
}
