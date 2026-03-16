<?php

namespace App\Filament\Widgets\Dashboard\Orders;

use App\Facades\DB;
use App\Models\Currency;
use App\Models\Order;
use Carbon\CarbonInterface;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Contracts\Support\Htmlable;

class OrdersSalesChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $pollingInterval = '60s';

    protected ?string $heading = null;

    public function getHeading(): Htmlable|string|null
    {
        return $this->heading ?? __('admin::widgets.dashboard.orders.order_sales_chart.heading');
    }

    protected function getOrderQuery(\DateTime|CarbonInterface|null $from = null, \DateTime|CarbonInterface|null $to = null)
    {
        return Order::whereNotNull('placed_at')
            ->with(['currency'])
            ->whereBetween('placed_at', [
                $from,
                $to,
            ]);
    }

    protected function getData(): array
    {
        $currency = Currency::getDefault();
        $date = now()->settings([
            'monthOverflow' => false,
        ]);
        $from = $date->clone()->subYear();

        $orders = $this->getOrderQuery($from, $date)
            ->select(
                DB::RAW('MAX(currency_code) as currency_code'),
                DB::RAW('COUNT(*) as count'),
                DB::RAW('SUM(sub_total) as sub_total'),
                DB::RAW(db_date('placed_at', '%M %Y', 'date')),
                DB::RAW(db_date('placed_at', '%Y-%m', 'sort_date')),
            )->groupBy(
                DB::RAW('date'),
                DB::RAW('sort_date'),
            )->orderBy(DB::RAW('sort_date'), 'asc')->get();

        $labels = $orders->pluck('date')->toArray();
        $ordersData = $orders->pluck('count')->toArray();
        $salesData = $orders->map(fn ($order) => $order->sub_total->decimal)->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('admin::widgets.dashboard.orders.order_sales_chart.series_one.label'),
                    'data' => $ordersData,
                ],
                [
                    'label' => __('admin::widgets.dashboard.orders.order_sales_chart.series_two.label', ['currency' => $currency->code]),
                    'data' => $salesData,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
