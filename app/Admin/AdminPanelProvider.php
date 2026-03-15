<?php

namespace App\Admin;

use Filament\Support\Events\FilamentUpgraded;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\NoPendingMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Admin\Auth\Manifest;
use App\Admin\Console\Commands\MakeAdminCommand;
use App\Admin\Database\State\EnsureBaseRolesAndPermissions;
use App\Admin\Events\ChildCollectionCreated;
use App\Admin\Events\CollectionProductDetached;
use App\Admin\Events\CustomerAddressEdited;
use App\Admin\Events\CustomerUserEdited;
use App\Admin\Events\ModelChannelsUpdated;
use App\Admin\Events\ModelPricesUpdated;
use App\Admin\Events\ModelUrlsUpdated;
use App\Admin\Events\ProductAssociationsUpdated;
use App\Admin\Events\ProductCollectionsUpdated;
use App\Admin\Events\ProductCustomerGroupsUpdated;
use App\Admin\Events\ProductPricingUpdated;
use App\Admin\Events\ProductVariantOptionsUpdated;
use App\Admin\Listeners\FilamentUpgradedListener;
use App\Admin\Models\Staff;
use App\Admin\Support\ActivityLog\Manifest as ActivityLogManifest;
use App\Admin\Support\Forms\AttributeData;
use App\Admin\Support\Synthesizers\PriceSynth;

class AdminPanelProvider extends ServiceProvider
{
    protected $configFiles = [
        'panel',
    ];

    public function register(): void
    {
        $this->app->scoped('admin-panel', function (): AdminPanelManager {
            return new AdminPanelManager;
        });

        $this->app->scoped('admin-access-control', function (): Manifest {
            return new Manifest;
        });

        $this->app->scoped('admin-activity-log', function (): ActivityLogManifest {
            return new ActivityLogManifest;
        });

        $this->app->scoped('admin-attribute-data', function (): AttributeData {
            return new AttributeData;
        });
    }

    public function boot(): void
    {
        if (! config('store.database.disable_migrations', false)) {
            $this->loadMigrationsFrom(database_path('migrations'));
        }

        $this->loadViewsFrom(resource_path('views/vendor/admin'), 'admin');

        $this->loadTranslationsFrom(lang_path('vendor/admin'), 'admin');

        $this->publishes([
            resource_path('views/vendor/admin') => resource_path('views/vendor/admin'),
            lang_path('vendor/admin') => lang_path('vendor/admin'),
        ]);

        $this->publishes([
            resource_path('views/vendor/admin/pdf') => resource_path('views/vendor/admin/pdf'),
        ], 'admin.pdf');

        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom(config_path("store/$config.php"), "store.$config");
        });

        if ($this->app->runningInConsole()) {
            collect($this->configFiles)->each(function ($config) {
                $this->publishes([
                    config_path("store/$config.php") => config_path("store/$config.php"),
                ], 'store');
            });

            $this->commands([
                MakeAdminCommand::class,
            ]);
        }

        Relation::morphMap([
            'staff' => Staff::class,
        ]);

        Event::listen([
            ChildCollectionCreated::class,
            CollectionProductDetached::class,
            CustomerAddressEdited::class,
            CustomerUserEdited::class,
            ProductAssociationsUpdated::class,
            ProductCollectionsUpdated::class,
            ProductPricingUpdated::class,
            ProductCustomerGroupsUpdated::class,
            ProductVariantOptionsUpdated::class,
            ModelChannelsUpdated::class,
            ModelPricesUpdated::class,
            ModelUrlsUpdated::class,
        ], fn ($event) => sync_with_search($event->model));

        $this->publishes([
            public_path('vendor/admin') => public_path('vendor/admin'),
        ], 'public');

        $this->registerAuthGuard();
        $this->registerPermissionManifest();
        $this->registerStateListeners();
        $this->registerPanelSynthesizer();
        // $this->registerUpgradedListener();
    }

    /**
     * Register our auth guard.
     */
    protected function registerAuthGuard(): void
    {
        $this->app['config']->set('auth.providers.staff', [
            'driver' => 'eloquent',
            'model' => Staff::class,
        ]);

        $this->app['config']->set('auth.guards.staff', [
            'driver' => 'session',
            'provider' => 'staff',
        ]);
    }

    /**
     * Register our permissions manifest.
     */
    protected function registerPermissionManifest(): void
    {
        Gate::after(function ($user, $ability) {
            // Are we trying to authorize something within the admin panel?
            $permission = $this->app->get('admin-access-control')->getPermissions()->first(fn ($permission) => $permission->handle === $ability);
            if ($permission) {
                return $user->admin || $user->hasPermissionTo($ability);
            }
        });
    }

    protected function registerUpgradedListener(): void
    {
        Event::listen(FilamentUpgraded::class, FilamentUpgradedListener::class);
    }

    protected function registerStateListeners()
    {
        $states = [
            EnsureBaseRolesAndPermissions::class,
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

    protected function registerPanelSynthesizer(): void
    {
        \App\Admin\Support\Facades\AttributeData::synthesizeLivewireProperties();
        Livewire::propertySynthesizer(PriceSynth::class);
    }
}
