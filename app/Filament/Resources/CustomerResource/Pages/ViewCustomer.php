<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Support\Pages\BaseViewRecord;
use Filament\Actions;

class ViewCustomer extends BaseViewRecord
{
    protected static string $resource = CustomerResource::class;

    public function getHeading(): string
    {
        return "{$this->record->first_name} {$this->record->last_name}";
    }

    public function getSubheading(): ?string
    {
        return $this->record->company_name;
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getDefaultHeaderWidgets(): array
    {
        return [
            CustomerResource\Widgets\CustomerStatsOverviewWidget::class,
        ];
    }
}
