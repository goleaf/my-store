<?php
/** @noinspection DuplicatedCode */

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryZoneResource\Pages;
use App\Http\Requests\Filament\Shipping\DeliveryZoneRequest;
use App\Models\Store\Models\DeliveryZone;
use App\Support\Resources\BaseResource;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Tables;
use Filament\Tables\Table;

class DeliveryZoneResource extends BaseResource
{
    protected static ?string $model = DeliveryZone::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-truck';

    public static function getLabel(): string
    {
        return 'Delivery Zone';
    }

    public static function getPluralLabel(): string
    {
        return 'Delivery Zones';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    protected static function getMainFormComponents(): array
    {
        $request = static::request();

        return [
            Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->rules($request->fieldRules('name'))
                    ->required($request->fieldHasRule('name', 'required')),
                Forms\Components\TextInput::make('min_order')
                    ->type('number')
                    ->inputMode('decimal')
                    ->step('0.0001')
                    ->rules($request->fieldRules('min_order'))
                    ->required($request->fieldHasRule('min_order', 'required'))
                    ->prefix('$')
                    ->default(0),
                Forms\Components\TextInput::make('delivery_fee')
                    ->type('number')
                    ->inputMode('decimal')
                    ->step('0.0001')
                    ->rules($request->fieldRules('delivery_fee'))
                    ->required($request->fieldHasRule('delivery_fee', 'required'))
                    ->prefix('$')
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->rules($request->fieldRules('is_active'))
                    ->required($request->fieldHasRule('is_active', 'required'))
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
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('min_order')
                ->money('USD')
                ->sortable(),
            Tables\Columns\TextColumn::make('delivery_fee')
                ->money('USD')
                ->sortable(),
            Tables\Columns\IconColumn::make('is_active')
                ->boolean(),
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
            'index' => Pages\ListDeliveryZones::route('/'),
            'create' => Pages\CreateDeliveryZone::route('/create'),
            'edit' => Pages\EditDeliveryZone::route('/{record}/edit'),
        ];
    }

    protected static function request(): DeliveryZoneRequest
    {
        return new DeliveryZoneRequest;
    }
}
