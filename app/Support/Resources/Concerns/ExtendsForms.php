<?php

namespace App\Support\Resources\Concerns;

use Filament\Schemas\Schema;

trait ExtendsForms
{
    public static function form(Schema $schema): Schema
    {
        return self::callStaticStoreHook('extendForm', static::getDefaultForm($schema));
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema->components(static::getMainFormComponents());
    }

    protected static function getMainFormComponents(): array
    {
        return [];
    }
}
