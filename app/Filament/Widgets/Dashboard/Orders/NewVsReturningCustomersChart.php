<?php

namespace App\Filament\Widgets\Dashboard\Orders;

use App\Facades\DB;
use App\Models\Order;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class NewVsReturningCustomersChart extends ChartWidget
{
    protected ?string $pollingInterval = '60s';

    protected ?string $heading = null;

    public function getHeading(): Htmlable|string|null
    {
        return $this->heading ?? __('admin::widgets.dashboard.orders.new_returning_customers.heading');
    }

    protected function getOrderQuery(\DateTime|CarbonInterface|null $from = null, \DateTime|CarbonInterface|null $to = null)
    {
        return Order::whereNotNull('placed_at')
            ->whereBetween('placed_at', [$from, $to]);
    }

    protected function getData(): array
    {
        $date = now()->settings(['monthOverflow' => false]);
        $from = $date->clone()->subYear();
        $period = CarbonPeriod::create($from, '1 month', $date);

        $results = $this->getOrderQuery($from, $date)
            ->select(
                DB::RAW('SUM(CASE WHEN new_customer THEN 1 ELSE 0 END) as new_customer_count'),
                DB::RAW('SUM(CASE WHEN NOT new_customer THEN 1 ELSE 0 END) as returning_customer_count'),
                DB::RAW(db_date('placed_at', '%Y%m', 'monthstamp'))
            )->groupBy(
                DB::RAW('monthstamp'),
                DB::RAW(db_date('placed_at', '%Y-%m')),
            )->orderBy(DB::RAW(db_date('placed_at', '%Y-%m')), 'desc')->get();

        $labels = [];
        $newCustomers = [];
        $returningCustomers = [];

        foreach ($period as $dateItem) {
            $labels[] = $dateItem->format('F Y');
            $report = $results->first(fn ($month) => $month->monthstamp == $dateItem->format('Ym'));
            $returningCustomers[] = (int) ($report?->returning_customer_count ?? 0);
            $newCustomers[] = (int) ($report?->new_customer_count ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => __('admin::widgets.dashboard.orders.new_returning_customers.series_one.label'),
                    'data' => $newCustomers,
                ],
                [
                    'label' => __('admin::widgets.dashboard.orders.new_returning_customers.series_two.label'),
                    'data' => $returningCustomers,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
