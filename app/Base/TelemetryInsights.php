<?php

namespace App\Base;

use Illuminate\Support\Collection;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class TelemetryInsights implements ProvidesTelemetryInsights
{
    public function domainHash(): string
    {
        return md5(
            config('app.url')
        );
    }

    public function environment(): string
    {
        return app()->environment();
    }

    public function laravelVersion(): string
    {
        return app()->version();
    }

    public function dbDriver(): string
    {
        return config('database.default');
    }

    public function phpVersion(): string
    {
        return phpversion();
    }

    public function productCount(): int
    {
        return Product::count();
    }

    public function productVariantCount(): int
    {
        return ProductVariant::count();
    }

    public function orderCount(): int
    {
        return Order::whereBetween('placed_at', [now()->subHours(24), now()])->count();
    }

    public function orderTotal(): int
    {
        return Order::whereBetween('placed_at', [now()->subHours(24), now()])->sum('total');
    }

    public function currencies(): Collection
    {
        return Currency::where('enabled', true)->get()->map(
            fn (Currency $currency) => $currency->code
        );
    }

    public function languages(): Collection
    {
        return Language::all()->map(
            fn (Language $language) => $language->code
        );
    }
}
