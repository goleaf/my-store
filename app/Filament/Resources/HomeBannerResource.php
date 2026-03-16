<?php

namespace App\Filament\Resources;

use App\Base\Enums\HomeBannerType;
use App\Filament\Resources\HomeBannerResource\Pages;
use App\Models\HomeBanner;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Actions;
use Filament\Schemas\Components;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;

class HomeBannerResource extends BaseResource
{
    protected static ?string $model = HomeBanner::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-image';

    public static function getLabel(): string
    {
        return 'Home Banner';
    }

    public static function getPluralLabel(): string
    {
        return 'Home Banners';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Home Page';
    }

    protected static function getMainFormComponents(): array
    {
        return [
            Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtitle')
                    ->maxLength(255),
                Forms\Components\TextInput::make('link')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('home-banners')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(HomeBannerType::options())
                    ->required()
                    ->default(HomeBannerType::Middle->value),
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
            Tables\Columns\TextColumn::make('type')
                ->formatStateUsing(fn ($state) => HomeBannerType::labelFor($state)),
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
            'index' => Pages\ListHomeBanners::route('/'),
            'create' => Pages\CreateHomeBanner::route('/create'),
            'edit' => Pages\EditHomeBanner::route('/{record}/edit'),
        ];
    }
}
