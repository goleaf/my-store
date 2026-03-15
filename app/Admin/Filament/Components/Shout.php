<?php

namespace App\Admin\Filament\Components;

use Filament\Forms\Components\ViewField;

class Shout extends ViewField
{
    protected string $view = 'admin::forms.components.shout';

    public function content(mixed $content): static
    {
        $this->viewData(['content' => $content]);

        return $this;
    }

    public function type(string $type): static
    {
        $this->viewData(['type' => $type]);

        return $this;
    }
}
