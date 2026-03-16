<?php

use App\Base\Enums\DeliverySlotDayType;
use App\Base\Enums\HomeBannerType;
use App\Base\Enums\HomeSectionType;
use App\Base\Enums\PostStatus;
use App\Base\Enums\ProductStatus;
use App\Base\Enums\ProductVariantPurchasable;
use App\Base\Enums\SavedPaymentMethodType;
use App\Base\Enums\TransactionType;
use App\Shipping\Enums\ShippingMethodChargeBy;
use App\Shipping\Enums\ShippingMethodDriver;
use App\Shipping\Enums\ShippingZoneType;

function translationValueForLocale(string $locale, string $translationKey): mixed
{
    [$namespace, $path] = explode('::', $translationKey, 2);
    $segments = explode('.', $path);
    $file = array_shift($segments);

    $basePath = match ($namespace) {
        'admin' => base_path("lang/{$locale}/admin/{$file}.php"),
        'store' => base_path("lang/{$locale}/store/{$file}.php"),
        'admin.shipping' => base_path("lang/{$locale}/admin/shipping/{$file}.php"),
        default => throw new InvalidArgumentException("Unsupported translation namespace [{$namespace}]"),
    };

    expect(is_file($basePath))->toBeTrue();

    $translations = include $basePath;

    return collect($segments)->reduce(
        fn ($value, $segment) => is_array($value) && array_key_exists($segment, $value) ? $value[$segment] : null,
        $translations,
    );
}

function flattenTranslationKeys(array $translations, string $prefix = ''): array
{
    $keys = [];

    foreach ($translations as $key => $value) {
        $path = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

        if (is_array($value)) {
            $keys = array_merge($keys, flattenTranslationKeys($value, $path));
        } else {
            $keys[] = $path;
        }
    }

    sort($keys);

    return $keys;
}

test('targeted enum translations exist for every active locale', function () {
    $locales = ['de', 'en', 'es', 'fr', 'hu', 'nl', 'pl', 'pt_BR', 'ro', 'tr', 'vi'];
    $enumClasses = [
        ProductStatus::class,
        PostStatus::class,
        TransactionType::class,
        SavedPaymentMethodType::class,
        DeliverySlotDayType::class,
        ProductVariantPurchasable::class,
        HomeBannerType::class,
        HomeSectionType::class,
        ShippingMethodChargeBy::class,
        ShippingMethodDriver::class,
        ShippingZoneType::class,
    ];

    foreach ($locales as $locale) {
        foreach ($enumClasses as $enumClass) {
            foreach ($enumClass::cases() as $case) {
                $translation = translationValueForLocale($locale, $case->translationKey());

                expect($translation)
                    ->not->toBeNull()
                    ->and($translation)->toBeString()
                    ->and(trim($translation))->not->toBe('');
            }
        }
    }
});

test('backfilled german and dutch shipping translation files match the english keyset', function (string $locale, string $file) {
    $englishTranslations = include base_path("lang/en/admin/shipping/{$file}.php");
    $localeTranslations = include base_path("lang/{$locale}/admin/shipping/{$file}.php");

    expect(flattenTranslationKeys($localeTranslations))
        ->toBe(flattenTranslationKeys($englishTranslations));
})->with([
    ['de', 'plugin'],
    ['de', 'relationmanagers'],
    ['de', 'shippingexclusionlist'],
    ['de', 'shippingmethod'],
    ['de', 'shippingzone'],
    ['nl', 'plugin'],
    ['nl', 'relationmanagers'],
    ['nl', 'shippingexclusionlist'],
    ['nl', 'shippingmethod'],
    ['nl', 'shippingzone'],
]);
