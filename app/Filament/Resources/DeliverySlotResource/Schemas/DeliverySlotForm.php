<?php

namespace App\Filament\Resources\DeliverySlotResource\Schemas;

use App\Base\Enums\DeliverySlotDayType;
use App\Http\Requests\Filament\Shipping\DeliverySlotRequest;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DeliverySlotForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                TextInput::make('zone_id')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('zone_id'))
                    ->required($request->fieldHasRule('zone_id', 'required')),
                Select::make('day_type')
                    ->options(DeliverySlotDayType::options())
                    ->rules($request->fieldRules('day_type'))
                    ->required($request->fieldHasRule('day_type', 'required'))
                    ->default(DeliverySlotDayType::Recurring->value)
                    ->live(),
                DatePicker::make('specific_date')
                    ->visible(fn (Get $get): bool => $get('day_type') === DeliverySlotDayType::Specific->value)
                    ->rules(fn (Get $get): array => static::request($get('day_type'))->fieldRules('specific_date'))
                    ->required(fn (Get $get): bool => static::request($get('day_type'))->fieldHasRule('specific_date', 'required')),
                TextInput::make('day_of_week')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->visible(fn (Get $get): bool => $get('day_type') === DeliverySlotDayType::Recurring->value)
                    ->rules(fn (Get $get): array => static::request($get('day_type'))->fieldRules('day_of_week'))
                    ->required(fn (Get $get): bool => static::request($get('day_type'))->fieldHasRule('day_of_week', 'required')),
                TextInput::make('label')
                    ->rules($request->fieldRules('label'))
                    ->required($request->fieldHasRule('label', 'required')),
                TimePicker::make('start_time')
                    ->rules($request->fieldRules('start_time'))
                    ->required($request->fieldHasRule('start_time', 'required')),
                TimePicker::make('end_time')
                    ->rules($request->fieldRules('end_time'))
                    ->required($request->fieldHasRule('end_time', 'required')),
                TextInput::make('cutoff_hours')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('cutoff_hours'))
                    ->required($request->fieldHasRule('cutoff_hours', 'required'))
                    ->default(0),
                TextInput::make('fee')
                    ->type('number')
                    ->inputMode('decimal')
                    ->step('0.01')
                    ->rules($request->fieldRules('fee'))
                    ->required($request->fieldHasRule('fee', 'required'))
                    ->default(0),
                TextInput::make('capacity')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('capacity'))
                    ->required($request->fieldHasRule('capacity', 'required'))
                    ->default(50),
                TextInput::make('booked_count')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('booked_count'))
                    ->required($request->fieldHasRule('booked_count', 'required'))
                    ->default(0),
                Toggle::make('is_active')
                    ->rules($request->fieldRules('is_active'))
                    ->required($request->fieldHasRule('is_active', 'required')),
            ]);
    }

    protected static function request(?string $dayType = null): DeliverySlotRequest
    {
        return (new DeliverySlotRequest)->forDayType($dayType);
    }
}
