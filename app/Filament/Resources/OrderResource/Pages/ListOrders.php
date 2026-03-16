<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Support\Pages\BaseListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends BaseListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getDefaultTabs(): array
    {
        $statuses = collect(
            config('store.orders.statuses', [])
        )->filter(
            fn ($config) => $config['favourite'] ?? false
        );

        return [
            'all' => Tab::make(__('admin::order.tabs.all')),
            ...collect($statuses)->mapWithKeys(
                fn ($config, $status) => [
                    $status => Tab::make($config['label'])
                        ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status)),
                ]
            ),
        ];
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
