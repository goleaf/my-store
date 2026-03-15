<?php

namespace App\Filament\Resources\ProductOptionResource\Pages;

use App\Admin\Filament\Resources\ProductOptionResource\RelationManagers;
use App\Filament\Resources\ProductOptionResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;

class EditProductOption extends BaseEditRecord
{
    protected static string $resource = ProductOptionResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return $this->record->shared ? [
            ProductOptionResource\RelationManagers\ValuesRelationManager::class,
        ] : [];
    }
}
