<?php

namespace App\Support\Resources\Concerns;

use Filament\Resources\RelationManagers\RelationGroup;

trait ExtendsRelationManagers
{
    /**
     * @return RelationGroup
     */
    public static function getRelations(): array
    {
        return static::callStaticStoreHook('getRelations', static::getDefaultRelations());
    }

    protected static function getDefaultRelations(): array
    {
        return [
            //
        ];
    }
}
