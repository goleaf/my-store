<?php

namespace App\Filament\Resources;

use App\Support\Resources\BaseResource;
use App\Support\Forms\Components\TranslatedText;
use App\Support\Tables\Columns\TranslatedTextColumn;
use App\Filament\Resources\HomeHeroResource\Pages;
use App\Models\HomeHero;
use BackedEnum;
use Filament\Forms;
use Filament\Actions;
use Filament\Schemas\Components;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HomeHeroResource extends BaseResource
{
    protected static ?string $model = HomeHero::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-monitor';

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->select([
            'id',
            'title',
            'subtitle',
            'description',
            'link',
            'button_text',
            'image',
            'sort_order',
            'is_active',
        ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            Components\Section::make()->schema([
                TranslatedText::make('title')
                    ->required()
                    ->maxLength(255),
                TranslatedText::make('subtitle')
                    ->maxLength(255),
                TranslatedText::make('description')
                    ->maxLength(65535),
                TranslatedText::make('link')
                    ->url()
                    ->maxLength(255),
                TranslatedText::make('button_text')
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
            TranslatedTextColumn::make('title')
                ->searchable(),
            TranslatedTextColumn::make('subtitle')
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
