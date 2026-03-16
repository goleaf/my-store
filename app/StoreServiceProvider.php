<?php

namespace App\Store;

use Cartalyst\Converter\Laravel\Facades\Converter;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\NoPendingMigrations;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use App\Store\Addons\Manifest;
use App\Store\Base\AttributeManifest;
use App\Store\Base\AttributeManifestInterface;
use App\Store\Base\CartLineModifiers;
use App\Store\Base\CartModifiers;
use App\Store\Base\CartSessionInterface;
use App\Store\Base\DiscountManagerInterface;
use App\Store\Base\FieldTypeManifest;
use App\Store\Base\FieldTypeManifestInterface;
use App\Store\Base\ModelManifest;
use App\Store\Base\ModelManifestInterface;
use App\Store\Base\OrderModifiers;
use App\Store\Base\OrderReferenceGenerator;
use App\Store\Base\OrderReferenceGeneratorInterface;
use App\Store\Base\PaymentManagerInterface;
use App\Store\Base\PricingManagerInterface;
use App\Store\Base\ProvidesTelemetryInsights;
use App\Store\Base\ShippingManifest;
use App\Store\Base\ShippingManifestInterface;
use App\Store\Base\ShippingModifiers;
use App\Store\Base\StorefrontSessionInterface;
use App\Store\Base\TaxManagerInterface;
use App\Store\Base\TelemetryInsights;
use App\Store\Base\TelemetryService;
use App\Store\Base\TelemetryServiceInterface;
use App\Console\Commands\AddonsDiscover;
use App\Store\Console\Commands\Import\AddressData;
use App\Console\Commands\MigrateGetCandy;
use App\Console\Commands\Orders\SyncNewCustomerOrders;
use App\Console\Commands\PruneCarts;
use App\Console\Commands\ScoutIndexerCommand;
use App\Console\Commands\InstallStore;
use App\Store\Database\State\ConvertBackOrderPurchasability;
use App\Store\Database\State\ConvertProductTypeAttributesToProducts;
use App\Store\Database\State\ConvertTaxbreakdown;
use App\Store\Database\State\EnsureBrandsAreUpgraded;
use App\Store\Database\State\EnsureDefaultTaxClassExists;
use App\Store\Database\State\EnsureMediaCollectionsAreRenamed;
use App\Store\Database\State\MigrateCartOrderRelationship;
use App\Store\Database\State\PopulateProductOptionLabelWithName;
use App\Store\Facades\Telemetry;
use App\Store\Listeners\CartSessionAuthListener;
use App\Store\Managers\CartSessionManager;
use App\Store\Managers\DiscountManager;
use App\Store\Managers\PaymentManager;
use App\Store\Managers\PricingManager;
use App\Store\Managers\StorefrontSessionManager;
use App\Store\Managers\TaxManager;
use App\Store\Models\Address;
use App\Store\Models\CartLine;
use App\Store\Models\Channel;
use App\Store\Models\Collection;
use App\Store\Models\Currency;
use App\Store\Models\Customer;
use App\Store\Models\CustomerGroup;
use App\Store\Models\Discount;
use App\Store\Models\Language;
use App\Store\Models\Order;
use App\Store\Models\OrderLine;
use App\Store\Models\Price;
use App\Store\Models\Product;
use App\Store\Models\ProductOption;
use App\Store\Models\ProductOptionValue;
use App\Store\Models\ProductVariant;
use App\Store\Models\Transaction;
use App\Store\Models\Url;
use App\Store\Observers\AddressObserver;
use App\Store\Observers\CartLineObserver;
use App\Store\Observers\ChannelObserver;
use App\Store\Observers\CollectionObserver;
use App\Store\Observers\CurrencyObserver;
use App\Store\Observers\CustomerGroupObserver;
use App\Store\Observers\CustomerObserver;
use App\Store\Observers\DiscountObserver;
use App\Store\Observers\LanguageObserver;
use App\Store\Observers\MediaObserver;
use App\Store\Observers\OrderLineObserver;
use App\Store\Observers\OrderObserver;
use App\Store\Observers\PriceObserver;
use App\Store\Observers\ProductObserver;
use App\Store\Observers\ProductOptionObserver;
use App\Store\Observers\ProductOptionValueObserver;
use App\Store\Observers\ProductVariantObserver;
use App\Store\Observers\TransactionObserver;
use App\Store\Observers\UrlObserver;

class StoreServiceProvider extends ServiceProvider
{
    protected $configFiles = [
        'cart',
        'cart_session',
        'database',
        'discounts',
        'media',
        'orders',
        'payments',
        'pricing',
        'products',
        'search',
        'shipping',
        'taxes',
        'urls',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(config_path("store/$config.php"), "store.$config");
        });

        $this->loadTranslationsFrom(lang_path('store'), 'store');

        $this->registerAddonManifest();

        $this->app->singleton(CartModifiers::class, function () {
            return new CartModifiers;
        });

        $this->app->singleton(CartLineModifiers::class, function () {
            return new CartLineModifiers;
        });

        $this->app->singleton(OrderModifiers::class, function () {
            return new OrderModifiers;
        });

        $this->app->singleton(CartSessionInterface::class, function ($app) {
            return $app->make(CartSessionManager::class);
        });

        $this->app->singleton(StorefrontSessionInterface::class, function ($app) {
            return $app->make(StorefrontSessionManager::class);
        });

        $this->app->singleton(ShippingModifiers::class, function ($app) {
            return new ShippingModifiers;
        });

        $this->app->singleton(ShippingManifestInterface::class, function ($app) {
            return $app->make(ShippingManifest::class);
        });

        $this->app->singleton(OrderReferenceGeneratorInterface::class, function ($app) {
            return $app->make(OrderReferenceGenerator::class);
        });

        $this->app->singleton(AttributeManifestInterface::class, function ($app) {
            return $app->make(AttributeManifest::class);
        });

        $this->app->singleton(FieldTypeManifestInterface::class, function ($app) {
            return $app->make(FieldTypeManifest::class);
        });

        $this->app->singleton(ModelManifestInterface::class, function ($app) {
            return $app->make(ModelManifest::class);
        });

        $this->app->bind(PricingManagerInterface::class, function ($app) {
            return $app->make(PricingManager::class);
        });

        $this->app->singleton(TaxManagerInterface::class, function ($app) {
            return $app->make(TaxManager::class);
        });

        $this->app->singleton(PaymentManagerInterface::class, function ($app) {
            return $app->make(PaymentManager::class);
        });

        $this->app->singleton(DiscountManagerInterface::class, function ($app) {
            return $app->make(DiscountManager::class);
        });

        $this->app->singleton(ProvidesTelemetryInsights::class, function ($app) {
            return $app->make(TelemetryInsights::class);
        });

        $this->app->singleton(TelemetryServiceInterface::class, function ($app) {
            return $app->make(TelemetryService::class);
        });

        $this->app->terminating(function () {
            if (! app()->runningInConsole()) {
                Telemetry::run();
            }
        });

        \App\Store\Facades\ModelManifest::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! config('store.database.disable_migrations', false)) {
            $this->loadMigrationsFrom(database_path('migrations'));
        }

        $this->registerObservers();
        $this->registerBuilderMacros();
        $this->registerBlueprintMacros();
        $this->registerStateListeners();

        \App\Store\Facades\ModelManifest::morphMap();

        if ($this->app->runningInConsole()) {
            collect($this->configFiles)->each(function ($config) {
                $this->publishes([
                    config_path("store/$config.php") => config_path("store/$config.php"),
                ], 'store');
            });

            $this->publishes([
                lang_path('store') => lang_path('store'),
            ], 'store.translation');

            $this->publishes([
                database_path('migrations') => database_path('migrations'),
            ], 'store.migrations');

            $this->commands([
                InstallStore::class,
                AddonsDiscover::class,
                AddressData::class,
                ScoutIndexerCommand::class,
                MigrateGetCandy::class,
                SyncNewCustomerOrders::class,
                PruneCarts::class,
            ]);

            if (config('store.cart.prune_tables.enabled', false)) {
                $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
                    $schedule->command('store:prune:carts')->daily();
                });
            }
        }

        Arr::macro('permutate', [\App\Store\Utils\Arr::class, 'permutate']);

        // Handle generator
        Str::macro('handle', function ($string) {
            return Str::slug($string, '_');
        });

        Converter::setMeasurements(
            config('store.shipping.measurements', [])
        );

        Event::listen(
            Login::class,
            [CartSessionAuthListener::class, 'login']
        );

        Event::listen(
            Logout::class,
            [CartSessionAuthListener::class, 'logout']
        );
    }

    protected function registerAddonManifest()
    {
        $this->app->instance(Manifest::class, new Manifest(
            new Filesystem,
            $this->app->basePath(),
            $this->app->bootstrapPath().'/cache/store_addons.php'
        ));
    }

    protected function registerStateListeners()
    {
        $states = [
            ConvertProductTypeAttributesToProducts::class,
            EnsureDefaultTaxClassExists::class,
            EnsureBrandsAreUpgraded::class,
            EnsureMediaCollectionsAreRenamed::class,
            PopulateProductOptionLabelWithName::class,
            MigrateCartOrderRelationship::class,
            ConvertTaxbreakdown::class,
            ConvertBackOrderPurchasability::class,
        ];

        foreach ($states as $state) {
            $class = new $state;

            Event::listen(
                [MigrationsStarted::class],
                [$class, 'prepare']
            );

            Event::listen(
                [MigrationsEnded::class, NoPendingMigrations::class],
                [$class, 'run']
            );
        }
    }

    /**
     * Register the observers used in Store.
     */
    protected function registerObservers(): void
    {
        Address::observe(AddressObserver::class);
        CartLine::observe(CartLineObserver::class);
        Channel::observe(ChannelObserver::class);
        Collection::observe(CollectionObserver::class);
        Currency::observe(CurrencyObserver::class);
        Customer::observe(CustomerObserver::class);
        CustomerGroup::observe(CustomerGroupObserver::class);
        Discount::observe(DiscountObserver::class);
        Language::observe(LanguageObserver::class);
        Order::observe(OrderObserver::class);
        OrderLine::observe(OrderLineObserver::class);
        Price::observe(PriceObserver::class);
        Product::observe(ProductObserver::class);
        ProductOption::observe(ProductOptionObserver::class);
        ProductOptionValue::observe(ProductOptionValueObserver::class);
        ProductVariant::observe(ProductVariantObserver::class);
        Transaction::observe(TransactionObserver::class);
        Url::observe(UrlObserver::class);

        if ($mediaModel = config('media-library.media_model')) {
            $mediaModel::observe(MediaObserver::class);
        }
    }

    protected function registerBuilderMacros(): void
    {
        Builder::macro('orderBySequence', function (array $ids) {
            /** @var Builder $this */
            $driver = $this->getConnection()->getDriverName();

            if (empty($ids)) {
                return $this;
            }

            if ($driver === 'mysql' || $driver === 'mariadb') {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));

                return $this->orderByRaw("FIELD(id, {$placeholders})", $ids);
            }

            if ($driver === 'pgsql') {
                $orderCases = '';
                foreach ($ids as $index => $id) {
                    $orderCases .= "WHEN id = $id THEN $index ";
                }

                return $this->orderByRaw("CASE $orderCases ELSE ".count($ids).' END');
            }

            return $this;
        });
    }

    /**
     * Register the blueprint macros.
     */
    protected function registerBlueprintMacros(): void
    {
        Blueprint::macro('scheduling', function () {
            /** @var Blueprint $this */
            $this->boolean('enabled')->default(false)->index();
            $this->timestamp('starts_at')->nullable()->index();
            $this->timestamp('ends_at')->nullable()->index();
        });

        Blueprint::macro('dimensions', function () {
            /** @var Blueprint $this */
            $columns = ['length', 'width', 'height', 'weight', 'volume'];
            foreach ($columns as $column) {
                $this->decimal("{$column}_value", 10, 4)->default(0)->nullable()->index();
                $this->string("{$column}_unit")->default('mm')->nullable();
            }
        });

        Blueprint::macro('userForeignKey', function ($field_name = 'user_id', $nullable = false) {
            /** @var Blueprint $this */
            $userModel = config('auth.providers.users.model');

            $type = config('store.database.users_id_type', 'bigint');

            if ($type == 'uuid') {
                $this->foreignUuid($field_name)
                    ->nullable($nullable)
                    ->constrained(
                        (new $userModel)->getTable()
                    );
            } elseif ($type == 'int') {
                $this->unsignedInteger($field_name)->nullable($nullable);
                $this->foreign($field_name)->references('id')->on('users');
            } else {
                $this->foreignId($field_name)
                    ->nullable($nullable)
                    ->constrained(
                        (new $userModel)->getTable()
                    );
            }
        });
    }
}
