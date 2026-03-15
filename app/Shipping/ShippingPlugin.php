<?php

namespace App\Shipping;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Support\Facades\FilamentIcon;
use App\Shipping\Filament\Resources\ShippingExclusionListResource;
use App\Shipping\Filament\Resources\ShippingMethodResource;
use App\Shipping\Filament\Resources\ShippingZoneResource;

class ShippingPlugin implements Plugin
{
    public function getId(): string
    {
        return 'shipping';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }

    public function register(Panel $panel): void
    {
        if (! config('store.shipping-tables.enabled')) {
            return;
        }

        $panel->navigationGroups([
            NavigationGroup::make('shipping')
                ->label(
                    fn () => __('admin.shipping::plugin.navigation.group')
                ),
        ])->resources([
            ShippingMethodResource::class,
            ShippingZoneResource::class,
            ShippingExclusionListResource::class,
        ]);

        FilamentIcon::register([
            'store::shipping-rates' => 'lucide-coins',
            'store::shipping-zones' => 'lucide-globe-2',
            'store::shipping-methods' => 'lucide-truck',
            'store::shipping-exclusion-lists' => 'lucide-package-minus',
        ]);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel;
    }

    // ...
}
