<?php

namespace App\Shipping;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use App\Base\ShippingModifiers;
use App\Facades\ModelManifest;
use App\Models\CustomerGroup;
use App\Models\Order;
use App\Models\Product;
use App\Shipping\Interfaces\ShippingMethodManagerInterface;
use App\Shipping\Managers\ShippingManager;
use App\Shipping\Models\ShippingExclusion;
use App\Shipping\Models\ShippingExclusionList;
use App\Shipping\Models\ShippingMethod;
use App\Shipping\Models\ShippingRate;
use App\Shipping\Models\ShippingZone;
use App\Shipping\Models\ShippingZonePostcode;
use App\Shipping\Observers\OrderObserver;

class ShippingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(config_path('store/shipping-tables.php'), 'store.shipping-tables');
    }

    public function boot(ShippingModifiers $shippingModifiers): void
    {
        if (! config('store.shipping-tables.enabled')) {
            return;
        }

        $this->loadTranslationsFrom(lang_path('admin/shipping'), 'admin.shipping');

        if (! config('store.database.disable_migrations', false)) {
            $this->loadMigrationsFrom(database_path('migrations'));
        }

        $this->loadViewsFrom(resource_path('views/shipping'), 'shipping');

        $shippingModifiers->add(
            ShippingModifier::class,
        );

        Order::observe(OrderObserver::class);

        Order::resolveRelationUsing('shippingZone', function ($orderModel) {
            $prefix = config('store.database.table_prefix');

            return $orderModel->belongsToMany(
                ShippingZone::class,
                "{$prefix}order_shipping_zone"
            )->withTimestamps();
        });

        CustomerGroup::resolveRelationUsing('shippingMethods', function ($customerGroup) {
            $prefix = config('store.database.table_prefix');

            return $customerGroup->belongsToMany(
                ShippingMethod::class,
                "{$prefix}customer_group_shipping_method"
            )->withTimestamps();
        });

        Product::resolveRelationUsing('shippingExclusions', function ($product) {
            return $product->morphMany(ShippingExclusion::class, 'purchasable');
        });

        $this->app->bind(ShippingMethodManagerInterface::class, function ($app) {
            return $app->make(ShippingManager::class);
        });

        ModelManifest::addDirectory(
            __DIR__ . '/Models'
        );

        Relation::morphMap([
            'shipping_exclusion' => ShippingExclusion::modelClass(),
            'shipping_exclusion_list' => ShippingExclusionList::modelClass(),
            'shipping_method' => ShippingMethod::modelClass(),
            'shipping_rate' => ShippingRate::modelClass(),
            'shipping_zone' => ShippingZone::modelClass(),
            'shipping_zone_postcode' => ShippingZonePostcode::modelClass(),
        ]);
    }
}
