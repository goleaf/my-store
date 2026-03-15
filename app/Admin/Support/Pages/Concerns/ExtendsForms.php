<?php

namespace App\Admin\Support\Pages\Concerns;

use Filament\Schemas\Schema;

trait ExtendsForms
{
    public function form(Schema $schema): Schema
    {
        return self::callStoreHook('extendForm', $this->getDefaultForm($schema));
    }

    public function getDefaultForm(Schema $schema): Schema
    {
        return $schema;
    }
}
