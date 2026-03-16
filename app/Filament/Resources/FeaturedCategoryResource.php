<?php

namespace App\Filament\Resources;

use App\Models\FeaturedCategory;
use App\Support\Resources\BaseResource;
use App\Filament\Resources\FeaturedCategoryResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class FeaturedCategoryResource extends BaseResource
{
    protected static ?string $model = FeaturedCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'lucide-layout-grid';

    public static function getLabel(): string
    {
        return 'Featured Category';
    }

    public static function getPluralLabel(): string
    {
        return 'Featured Categories';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Home Page';
    }

    protected static function getMainFormComponents(): array
    {
        return [
            SchemaComponents\Section::make()->schema([
                Forms\Components\Select::make('collection_id')
                    ->relationship('collection', 'id') // We will customize the title in the select
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->attribute_data['name']['value'] ?? $record->id)
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('featured-categories'),
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
            Tables\Columns\ImageColumn::make('image'),
            Tables\Columns\TextColumn::make('title')
                ->searchable(),
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
            'index' => Pages\ListFeaturedCategories::route('/'),
            'create' => Pages\CreateFeaturedCategory::route('/create'),
            'edit' => Pages\EditFeaturedCategory::route('/{record}/edit'),
        ];
    }
}
