<?php

namespace App\Providers;

use App\Filament\Extensions\ProductVariantsHeaderWidgetsExtension;
use App\Filament\Resources\ProductResource\Pages\ManageProductVariants;
use App\Modifiers\ShippingModifier;
use App\Shipping\ShippingPlugin;
use App\Base\ShippingModifiers;
use App\Support\Facades\AdminPanel;
use Illuminate\Support\ServiceProvider;

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

        \App\Facades\ModelManifest::replace(
            \App\Models\Contracts\Product::class,
            \App\Models\Product::class,
            // \App\Models\CustomProduct::class,
        );
    }
}
