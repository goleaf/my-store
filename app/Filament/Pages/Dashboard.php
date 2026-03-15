<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Dashboard\Orders\AverageOrderValueChart;
use App\Filament\Widgets\Dashboard\Orders\LatestOrdersTable;
use App\Filament\Widgets\Dashboard\Orders\NewVsReturningCustomersChart;
use App\Filament\Widgets\Dashboard\Orders\OrdersSalesChart;
use App\Filament\Widgets\Dashboard\Orders\OrderStatsOverview;
use App\Filament\Widgets\Dashboard\Orders\OrderTotalsChart;
use App\Filament\Widgets\Dashboard\Orders\PopularProductsTable;
use App\Support\Concerns\CallsHooks;
use App\Support\Pages\BaseDashboard;
use Filament\Support\Facades\FilamentIcon;

class Dashboard extends BaseDashboard
{
    use CallsHooks;

    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return self::callStoreHook('getWidgets', $this->getDefaultWidgets());
    }

    public function getDefaultWidgets(): array
    {
        return [
            ...$this->getDefaultOverviewWidgets(),
            ...$this->getDefaultChartsWidgets(),
            ...$this->getDefaultTableWidgets(),
        ];
    }

    public function getDefaultOverviewWidgets(): array
    {
        return self::callStoreHook('getOverviewWidgets', [
            OrderStatsOverview::class,
        ]);
    }

    public function getDefaultChartsWidgets(): array
    {
        return self::callStoreHook('getChartWidgets', [
            OrderTotalsChart::class,
            OrdersSalesChart::class,
            AverageOrderValueChart::class,
            NewVsReturningCustomersChart::class,
        ]);
    }

    public function getDefaultTableWidgets(): array
    {
        return self::callStoreHook('getTableWidgets', [
            PopularProductsTable::class,
            LatestOrdersTable::class,
        ]);
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::dashboard');
    }
}
