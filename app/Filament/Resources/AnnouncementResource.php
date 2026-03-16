<?php
/** @noinspection DuplicatedCode */

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Store\Models\Announcement;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class AnnouncementResource extends BaseResource
{
    protected static ?string $model = Announcement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'lucide-megaphone';

    public static function getLabel(): string
    {
        return 'Announcement';
    }

    public static function getPluralLabel(): string
    {
        return 'Announcements';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Home Page';
    }

    protected static function getMainFormComponents(): array
    {
        return [
            SchemaComponents\Section::make()->schema([
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\ColorPicker::make('bg_color')
                    ->label('Background Color'),
                Forms\Components\ColorPicker::make('text_color')
                    ->label('Text Color'),
                Forms\Components\DateTimePicker::make('starts_at'),
                Forms\Components\DateTimePicker::make('ends_at'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ])->columns(2),
        ];
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('message')
                ->limit(50)
                ->searchable(),
            Tables\Columns\IconColumn::make('is_active')
                ->boolean(),
            Tables\Columns\TextColumn::make('starts_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('ends_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\ColorColumn::make('bg_color')
                ->label('BG'),
            Tables\Columns\ColorColumn::make('text_color')
                ->label('Text'),
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
