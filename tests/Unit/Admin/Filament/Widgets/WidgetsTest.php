<?php

use App\Filament\Resources\ProductResource\Widgets\ProductOptionsWidget;
use App\Filament\Resources\CollectionGroupResource\Widgets\CollectionTreeView;
use App\Filament\Resources\CustomerResource\Widgets\CustomerStatsOverviewWidget;
use App\Filament\Widgets\Dashboard\Orders\AverageOrderValueChart;
use App\Filament\Widgets\Dashboard\Orders\LatestOrdersTable;
use App\Filament\Widgets\Dashboard\Orders\NewVsReturningCustomersChart;
use App\Filament\Widgets\Dashboard\Orders\OrdersSalesChart;
use App\Filament\Widgets\Dashboard\Orders\OrderStatsOverview;
use App\Filament\Widgets\Dashboard\Orders\OrderTotalsChart;
use App\Filament\Widgets\Dashboard\Orders\PopularProductsTable;
use App\Filament\Widgets\Products\VariantSwitcherTable;

it('can instantiate all dashboard order widgets', function () {
    expect(new OrderStatsOverview())->toBeInstanceOf(OrderStatsOverview::class);
    expect(new OrderTotalsChart())->toBeInstanceOf(OrderTotalsChart::class);
    expect(new OrdersSalesChart())->toBeInstanceOf(OrdersSalesChart::class);
    expect(new NewVsReturningCustomersChart())->toBeInstanceOf(NewVsReturningCustomersChart::class);
    expect(new AverageOrderValueChart())->toBeInstanceOf(AverageOrderValueChart::class);
    expect(new LatestOrdersTable())->toBeInstanceOf(LatestOrdersTable::class);
    expect(new PopularProductsTable())->toBeInstanceOf(PopularProductsTable::class);
});

it('can instantiate collection and customer widgets', function () {
    expect(new CollectionTreeView())->toBeInstanceOf(CollectionTreeView::class);
    expect(new CustomerStatsOverviewWidget())->toBeInstanceOf(CustomerStatsOverviewWidget::class);
});

it('can instantiate product widgets', function () {
    expect(new ProductOptionsWidget())->toBeInstanceOf(ProductOptionsWidget::class);
    expect(new VariantSwitcherTable())->toBeInstanceOf(VariantSwitcherTable::class);
});

