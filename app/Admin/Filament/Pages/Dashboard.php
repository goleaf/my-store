<?php

namespace App\Admin\Filament\Pages;

use Filament\Support\Facades\FilamentIcon;
use App\Admin\Filament\Widgets\Dashboard\Orders\AverageOrderValueChart;
use App\Admin\Filament\Widgets\Dashboard\Orders\LatestOrdersTable;
use App\Admin\Filament\Widgets\Dashboard\Orders\NewVsReturningCustomersChart;
use App\Admin\Filament\Widgets\Dashboard\Orders\OrdersSalesChart;
use App\Admin\Filament\Widgets\Dashboard\Orders\OrderStatsOverview;
use App\Admin\Filament\Widgets\Dashboard\Orders\OrderTotalsChart;
use App\Admin\Filament\Widgets\Dashboard\Orders\PopularProductsTable;
use App\Admin\Support\Concerns\CallsHooks;
use App\Admin\Support\Pages\BaseDashboard;

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
