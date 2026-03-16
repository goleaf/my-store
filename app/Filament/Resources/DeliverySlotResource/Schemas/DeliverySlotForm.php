<?php

namespace App\Filament\Resources\DeliverySlotResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DeliverySlotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('zone_id')
                    ->numeric(),
                TextInput::make('day_type')
                    ->required()
                    ->default('recurring'),
                DatePicker::make('specific_date'),
                TextInput::make('day_of_week')
                    ->numeric(),
                TextInput::make('label')
                    ->required(),
                TimePicker::make('start_time')
                    ->required(),
                TimePicker::make('end_time')
                    ->required(),
                TextInput::make('cutoff_hours')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('fee')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->default(50),
                TextInput::make('booked_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
