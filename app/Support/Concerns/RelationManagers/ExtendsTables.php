<?php

namespace App\Support\Concerns\RelationManagers;

use Filament\Tables\Table;

trait ExtendsTables
{
    public function table(Table $table): Table
    {
        return self::callStoreHook('extendTable', $this->getDefaultTable($table));
    }

    protected function getDefaultTable(Table $table): Table
    {
        return $table;
    }
}
