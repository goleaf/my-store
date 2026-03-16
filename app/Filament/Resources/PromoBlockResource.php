<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoBlockResource\Pages\CreatePromoBlock;
use App\Filament\Resources\PromoBlockResource\Pages\EditPromoBlock;
use App\Filament\Resources\PromoBlockResource\Pages\ListPromoBlocks;
use App\Filament\Resources\PromoBlockResource\Schemas\PromoBlockForm;
use App\Filament\Resources\PromoBlockResource\Tables\PromoBlocksTable;
use App\Models\PromoBlock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromoBlockResource extends Resource
{
    protected static ?string $model = PromoBlock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PromoBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromoBlocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPromoBlocks::route('/'),
            'create' => CreatePromoBlock::route('/create'),
            'edit' => EditPromoBlock::route('/{record}/edit'),
        ];
    }
}
