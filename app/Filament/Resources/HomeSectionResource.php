<?php

namespace App\Filament\Resources;

use App\Models\HomeSection;
use App\Support\Resources\BaseResource;
use App\Filament\Resources\HomeSectionResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class HomeSectionResource extends BaseResource
{
    protected static ?string $model = HomeSection::class;

    protected static string|\BackedEnum|null $navigationIcon = 'lucide-layout-list';

    public static function getLabel(): string
    {
        return 'Home Section';
    }

    public static function getPluralLabel(): string
    {
        return 'Home Sections';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Home Page';
    }

    protected static function getMainFormComponents(): array
    {
        return [
            SchemaComponents\Section::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtitle')
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'product_grid' => 'Product Grid',
                        'sidebar_grid' => 'Sidebar Grid (Daily Best Sells)',
                        'featured_items' => 'Featured Items (4 boxes)',
                    ])
                    ->required()
                    ->default('product_grid'),
                Forms\Components\Select::make('collection_id')
                    ->relationship('collection', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->attribute_data['name']['value'] ?? $record->id)
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]),
        ];
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->searchable(),
            Tables\Columns\TextColumn::make('type'),
            Tables\Columns\TextColumn::make('collection.id')
                ->label('Collection')
                ->formatStateUsing(fn ($record) => $record->collection?->attribute_data['name']['value'] ?? $record->collection_id),
            Tables\Columns\IconColumn::make('is_active')
                ->boolean(),
            Tables\Columns\TextColumn::make('sort_order')
                ->numeric()
                ->sortable(),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListHomeSections::route('/'),
            'create' => Pages\CreateHomeSection::route('/create'),
            'edit' => Pages\EditHomeSection::route('/{record}/edit'),
        ];
    }
}
