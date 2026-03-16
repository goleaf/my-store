<?php

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Staff;
use App\Shipping\Filament\Resources\ShippingExclusionListResource;
use App\Shipping\Filament\Resources\ShippingMethodResource;
use App\Shipping\Filament\Resources\ShippingZoneResource;
use App\Shipping\Models\ShippingExclusionList;
use App\Shipping\Models\ShippingMethod;
use App\Shipping\Models\ShippingZone;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

function createDefaultCurrency(): Currency
{
    return Currency::factory()->create([
        'code' => 'USD',
        'name' => 'US Dollar',
        'exchange_rate' => 1,
        'decimal_places' => 2,
        'default' => true,
        'enabled' => true,
    ]);
}

function filamentResourceIndexPages(): array
{
    $projectRoot = dirname(__DIR__, 3);
    $appPath = $projectRoot . DIRECTORY_SEPARATOR . 'app';
    $resourceClasses = [];
    $disabledIndexResources = [
        'App\\Filament\\Resources\\CollectionResource',
    ];

    foreach ([
        $appPath . DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR . 'Resources',
        $appPath . DIRECTORY_SEPARATOR . 'Shipping' . DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR . 'Resources',
    ] as $directory) {
        if (! is_dir($directory)) {
            continue;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            if (
                ! str_ends_with($file->getFilename(), 'Resource.php')
                || str_contains($file->getPathname(), DIRECTORY_SEPARATOR . 'Pages' . DIRECTORY_SEPARATOR)
                || str_contains($file->getPathname(), DIRECTORY_SEPARATOR . 'RelationManagers' . DIRECTORY_SEPARATOR)
            ) {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($appPath) + 1);
            $class = 'App\\' . str_replace(
                [DIRECTORY_SEPARATOR, '.php'],
                ['\\', ''],
                $relativePath,
            );

            if (
                ! is_subclass_of($class, Resource::class)
                || in_array($class, $disabledIndexResources, true)
                || ! array_key_exists('index', $class::getPages())
            ) {
                continue;
            }

            try {
                $class::getUrl('index');
            } catch (Throwable) {
                continue;
            }

            $resourceClasses[] = $class;
        }
    }

    sort($resourceClasses);

    return array_map(
        fn (string $class): array => [$class],
        array_values(array_unique($resourceClasses)),
    );
}

beforeEach(function () {
    $this->staff = Staff::factory()->create([
        'admin' => true,
    ]);

    Permission::findOrCreate('catalog:manage-products', 'staff');
    Permission::findOrCreate('sales:manage-orders', 'staff');

    $this->staff->givePermissionTo([
        'catalog:manage-products',
        'sales:manage-orders',
    ]);
});

test('filament resources no longer reference removed filament 3 classes', function () {
    $legacySymbols = [
        'Filament\\Resources\\Components\\Tab',
        'Filament\\Forms\\Components\\Component',
        'Filament\\Forms\\ComponentContainer',
        'Filament\\Forms\\Concerns\\HasComponents',
        'Filament\\Forms\\Get',
        'Filament\\Forms\\Set',
        'Forms\\Get',
        'Forms\\Set',
        'Forms\\Components\\Grid',
        'Forms\\Components\\Section',
        'Filament\\Infolists\\Components\\Actions\\Action',
        'TextEntrySize',
        'ActionSize',
        'SpatieMediaLibraryImageColumn',
        'store.admin.livewire.components.activity-log-feed',
    ];

    $filesWithLegacySymbols = collect([
        app_path(),
        resource_path('views/admin'),
    ])
        ->flatMap(fn (string $path) => File::isDirectory($path) ? File::allFiles($path) : [])
        ->filter(fn ($file) => in_array($file->getExtension(), ['php', 'blade.php'], true))
        ->mapWithKeys(function ($file) use ($legacySymbols): array {
            $contents = $file->getContents();

            $matches = collect($legacySymbols)
                ->filter(fn (string $symbol): bool => str_contains($contents, $symbol))
                ->values()
                ->all();

            if ($matches === []) {
                return [];
            }

            return [$file->getPathname() => $matches];
        });

    expect($filesWithLegacySymbols)->toBeEmpty();
});

test('staff can open upgraded product and order resources', function () {
    createDefaultCurrency();

    $order = Order::factory()->create([
        'currency_code' => 'USD',
        'compare_currency_code' => 'USD',
    ]);

    $this->actingAs($this->staff, 'staff');

    $this->get(ProductResource::getUrl('index'))
        ->assertSuccessful();

    $this->get(OrderResource::getUrl('index'))
        ->assertSuccessful();

    $this->get(OrderResource::getUrl('order', ['record' => $order]))
        ->assertSuccessful();
});

test('staff can open filament resource index pages', function () {
    createDefaultCurrency();

    $this->actingAs($this->staff, 'staff');

    $resourceIndexPages = filamentResourceIndexPages();

    expect($resourceIndexPages)->not->toBeEmpty();

    foreach ($resourceIndexPages as [$resourceClass]) {
        $url = $resourceClass::getUrl('index');
        $response = $this->get($url);

        if (! $response->isSuccessful()) {
            throw new RuntimeException("Failed resource index page: {$resourceClass} ({$url}) returned {$response->status()}");
        }
    }
});

test('staff can open upgraded shipping resource pages', function () {
    createDefaultCurrency();

    $shippingZone = ShippingZone::factory()->create();
    $shippingMethod = ShippingMethod::factory()->create();
    $shippingExclusionList = ShippingExclusionList::factory()->create();

    $this->actingAs($this->staff, 'staff');

    $this->get(ShippingZoneResource::getUrl('edit', ['record' => $shippingZone]))
        ->assertSuccessful();

    $this->get(ShippingZoneResource::getUrl('rates', ['record' => $shippingZone]))
        ->assertSuccessful();

    $this->get(ShippingMethodResource::getUrl('edit', ['record' => $shippingMethod]))
        ->assertSuccessful();

    $this->get(ShippingExclusionListResource::getUrl('edit', ['record' => $shippingExclusionList]))
        ->assertSuccessful();
});
