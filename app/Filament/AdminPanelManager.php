<?php

namespace App\Filament;

use App\Admin\Filament\Pages;
use App\Admin\Filament\Resources;
use App\Filament\AvatarProviders\GravatarProvider;
use App\Filament\Widgets\Dashboard\Orders\AverageOrderValueChart;
use App\Filament\Widgets\Dashboard\Orders\LatestOrdersTable;
use App\Filament\Widgets\Dashboard\Orders\NewVsReturningCustomersChart;
use App\Filament\Widgets\Dashboard\Orders\OrdersSalesChart;
use App\Filament\Widgets\Dashboard\Orders\OrderStatsOverview;
use App\Filament\Widgets\Dashboard\Orders\OrderTotalsChart;
use App\Filament\Widgets\Dashboard\Orders\PopularProductsTable;
use App\Http\Controllers\DownloadPdfController;
use App\Support\Facades\AdminAccessControl;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Table;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelManager
{
    protected bool $twoFactorAuthForced = false;

    protected bool $twoFactorAuthDisabled = false;

    protected ?\Closure $closure = null;

    protected array $extensions = [];

    protected string $panelId = 'admin';

    protected static $resources = [
        \App\Filament\Resources\ActivityResource::class,
        \App\Filament\Resources\AttributeGroupResource::class,
        \App\Filament\Resources\BrandResource::class,
        \App\Filament\Resources\ChannelResource::class,
        \App\Filament\Resources\CollectionGroupResource::class,
        \App\Filament\Resources\CollectionResource::class,
        \App\Filament\Resources\CurrencyResource::class,
        \App\Filament\Resources\CustomerGroupResource::class,
        \App\Filament\Resources\CustomerResource::class,
        \App\Filament\Resources\DiscountResource::class,
        \App\Filament\Resources\LanguageResource::class,
        \App\Filament\Resources\OrderResource::class,
        \App\Filament\Resources\ProductOptionResource::class,
        \App\Filament\Resources\ProductResource::class,
        \App\Filament\Resources\ProductTypeResource::class,
        \App\Filament\Resources\ProductVariantResource::class,
        \App\Filament\Resources\StaffResource::class,
        \App\Filament\Resources\TagResource::class,
        \App\Filament\Resources\TaxClassResource::class,
        \App\Filament\Resources\TaxZoneResource::class,
        \App\Filament\Resources\TaxRateResource::class,
    ];

    protected static $pages = [
        \App\Filament\Pages\Dashboard::class,
    ];

    protected static $widgets = [
        OrderStatsOverview::class,
        OrderTotalsChart::class,
        OrdersSalesChart::class,
        AverageOrderValueChart::class,
        NewVsReturningCustomersChart::class,
        PopularProductsTable::class,
        LatestOrdersTable::class,
    ];

    public function register(): self
    {
        $panel = $this->defaultPanel();

        if ($this->closure instanceof \Closure) {
            $fn = $this->closure;
            $panel = $fn($panel);
        }

        Filament::registerPanel($panel);

        FilamentIcon::register([
            // Filament
            'panels::topbar.global-search.field' => 'lucide-search',
            'actions::view-action' => 'lucide-eye',
            'actions::edit-action' => 'lucide-edit',
            'actions::delete-action' => 'lucide-trash-2',
            'actions::make-collection-root-action' => 'lucide-corner-left-up',

            // Lunar
            'store::activity' => 'lucide-activity',
            'store::attributes' => 'lucide-pencil-ruler',
            'store::availability' => 'lucide-calendar',
            'store::basic-information' => 'lucide-edit',
            'store::brands' => 'lucide-badge-check',
            'store::channels' => 'lucide-store',
            'store::collections' => 'lucide-blocks',
            'store::sub-collection' => 'lucide-square-stack',
            'store::move-collection' => 'lucide-move',
            'store::currencies' => 'lucide-circle-dollar-sign',
            'store::customers' => 'lucide-users',
            'store::customer-groups' => 'lucide-users',
            'store::dashboard' => 'lucide-bar-chart-big',
            'store::discounts' => 'lucide-percent-circle',
            'store::discount-limitations' => 'lucide-list-x',
            'store::info' => 'lucide-info',
            'store::languages' => 'lucide-languages',
            'store::media' => 'lucide-image',
            'store::orders' => 'lucide-inbox',
            'store::product-pricing' => 'lucide-coins',
            'store::product-associations' => 'lucide-cable',
            'store::product-inventory' => 'lucide-combine',
            'store::product-options' => 'lucide-list',
            'store::product-shipping' => 'lucide-truck',
            'store::product-variants' => 'lucide-shapes',
            'store::products' => 'lucide-tag',
            'store::staff' => 'lucide-shield',
            'store::tags' => 'lucide-tags',
            'store::tax' => 'lucide-landmark',
            'store::urls' => 'lucide-globe',
            'store::product-identifiers' => 'lucide-package-search',
            'store::reorder' => 'lucide-grip-vertical',
            'store::chevron-right' => 'lucide-chevron-right',
            'store::image-placeholder' => 'lucide-image',
            'store::trending-up' => 'lucide-trending-up',
            'store::trending-down' => 'lucide-trending-down',
            'store::exclamation-circle' => 'lucide-alert-circle',
        ]);

        FilamentColor::register([
            'chartPrimary' => Color::Blue,
            'chartSecondary' => Color::Green,
        ]);

        if (app('request')->is($panel->getPath().'*')) {
            app('config')->set('livewire.inject_assets', true);
        }

        Table::configureUsing(function (Table $table): void {
            $table
                ->paginationPageOptions([10, 25, 50, 100])
                ->defaultPaginationPageOption(25);
        });

        return $this;
    }

    public function panel(\Closure $closure): self
    {
        $this->closure = $closure;

        return $this;
    }

    public function getPanel(): Panel
    {
        return Filament::getPanel($this->panelId);
    }

    public function forceTwoFactorAuth(bool $state = true): self
    {
        $this->twoFactorAuthForced = $state;

        return $this;
    }

    public function disableTwoFactorAuth(): self
    {
        $this->twoFactorAuthDisabled = true;

        return $this;
    }

    protected function defaultPanel(): Panel
    {
        $brandAsset = function ($asset) {
            $vendorPath = 'vendor/admin/';

            if (file_exists(public_path($vendorPath.$asset))) {
                return asset($vendorPath.$asset);
            }
            if (file_exists(public_path($asset))) {
                return asset($asset);
            }
            $type = str($asset)->endsWith('.png') ? 'image/png' : 'image/svg+xml';
            return "data:{$type};base64,".base64_encode('');
        };

        $panelMiddleware = [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];

        if (config('store.panel.pdf_rendering', 'download') == 'stream') {
            Route::get('lunar/pdf/download', DownloadPdfController::class)
                ->name('admin.pdf.download')->middleware($panelMiddleware);
        }

        $plugins = [];
        if (class_exists(\Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin::class)) {
            $plugins[] = \Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin::make();
        }
        if (! $this->twoFactorAuthDisabled && class_exists(\Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin::class)) {
            $plugins[] = \Stephenjude\FilamentTwoFactorAuthentication\TwoFactorAuthenticationPlugin::make()
                ->enableTwoFactorAuthentication()
                ->addTwoFactorMenuItem(label: '2FA Settings')
                ->forceTwoFactorSetup(condition: $this->twoFactorAuthForced);
        }

        return Panel::make()
            ->spa()
            ->default()
            ->id($this->panelId)
            ->brandName('Admin')
            ->brandLogo($brandAsset('admin-logo.svg'))
            ->darkModeBrandLogo($brandAsset('admin-logo-dark.svg'))
            ->favicon($brandAsset('admin-icon.png'))
            ->brandLogoHeight('2rem')
            ->path('admin')
            ->authGuard('staff')
            ->defaultAvatarProvider(GravatarProvider::class)
            ->login()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->font('Poppins')
            ->middleware($panelMiddleware)
            ->assets([
                Css::make('admin-panel', __DIR__.'/../resources/dist/admin-panel.css'),
            ], 'lunarphp/panel')
            ->pages(
                static::getPages()
            )
            ->resources(
                static::getResources()
            )
            ->discoverClusters(
                in: realpath(__DIR__.'/Filament/Clusters'),
                for: 'App\Admin\Filament\Clusters'
            )
            ->widgets(
                static::getWidgets()
            )
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins($plugins)
            ->discoverLivewireComponents(__DIR__.'/Livewire', 'App\\Admin\\Livewire')
            ->livewireComponents([
                \App\Filament\Resources\OrderResource\Pages\Components\OrderItemsTable::class,
                \App\Filament\Resources\CollectionGroupResource\Widgets\CollectionTreeView::class,
            ])
            ->navigationGroups([
                'Catalog',
                'Sales',
                NavigationGroup::make()
                    ->label('Settings')
                    ->collapsed(),
            ])->sidebarCollapsibleOnDesktop();
    }

    public function extensions(array $extensions): self
    {
        foreach ($extensions as $class => $extension) {
            if (! is_array($extension)) {
                $extension = [$extension];
            }

            $this->extensions[$class] = [
                ...$this->extensions[$class] ?? [],
                ...collect($extension)->reject(
                    fn ($extension) => ! class_exists($extension)
                )->map(
                    fn ($extension) => app($extension)
                )->values()->toArray(),
            ];
        }

        return $this;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @return array<class-string<\Filament\Resources\Resource>>
     */
    public static function getResources(): array
    {
        return static::$resources;
    }

    /**
     * @return array<class-string<\Filament\Pages\Page>>
     */
    public static function getPages(): array
    {
        return static::$pages;
    }

    /**
     * @return array<class-string<\Filament\Widgets\Widget>>
     */
    public static function getWidgets(): array
    {
        return static::$widgets;
    }

    public function useRoleAsAdmin(string|array $roleHandle): self
    {
        AdminAccessControl::useRoleAsAdmin($roleHandle);

        return $this;
    }

    public function callHook(string $class, ?object $caller, string $hookName, ...$args): mixed
    {
        if (isset($this->extensions[$class])) {
            foreach ($this->extensions[$class] as $extension) {
                if (method_exists($extension, $hookName)) {
                    $extension->setCaller($caller);
                    $args[0] = $extension->{$hookName}(...$args);
                }
            }
        }

        return $args[0];
    }
}
