<?php

namespace App\Providers;

use App\Filament\Extensions\ProductVariantsHeaderWidgetsExtension;
use App\Modifiers\ShippingModifier;
use Illuminate\Support\ServiceProvider;
use App\Admin\Filament\Resources\ProductResource\Pages\ManageProductVariants;
use App\Admin\Support\Facades\AdminPanel;
use App\Store\Base\ShippingModifiers;
use App\Shipping\ShippingPlugin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        AdminPanel::panel(
            fn ($panel) => $panel->plugins([
                new ShippingPlugin,
            ])
        )
            ->extensions([
                ManageProductVariants::class => [
                    ProductVariantsHeaderWidgetsExtension::class,
                ],
            ])
            ->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ShippingModifiers $shippingModifiers): void
    {
        $shippingModifiers->add(
            ShippingModifier::class
        );

        \App\Store\Facades\ModelManifest::replace(
            \App\Store\Models\Contracts\Product::class,
            \App\Models\Product::class,
            // \App\Models\CustomProduct::class,
        );
    }
}
