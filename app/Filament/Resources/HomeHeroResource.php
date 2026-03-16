<?php

namespace App\Filament\Resources;

use App\Store\Models\HomeHero;
use App\Support\Resources\BaseResource;
use App\Filament\Resources\HomeHeroResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class HomeHeroResource extends BaseResource
{
    protected static ?string $model = HomeHero::class;

    protected static string|\BackedEnum|null $navigationIcon = 'lucide-monitor';

    public static function getLabel(): string
    {
        return 'Home Hero';
    }

    public static function getPluralLabel(): string
    {
        return 'Home Heroes';
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
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtitle')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('button_text')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('home-heroes'),
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
            Tables\Columns\TextColumn::make('subtitle')
                ->searchable(),
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
            'index' => Pages\ListHomeHeroes::route('/'),
            'create' => Pages\CreateHomeHero::route('/create'),
            'edit' => Pages\EditHomeHero::route('/{record}/edit'),
        ];
    }
}
