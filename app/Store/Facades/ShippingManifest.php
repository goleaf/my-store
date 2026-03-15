<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\ShippingManifestInterface;

/**
 * @method static void addOption(\Lunar\DataTypes\ShippingOption $option)
 * @method static void addOptions(\Illuminate\Support\Collection $options)
 * @method static void clearOptions()
 * @method static \Lunar\Base\ShippingManifest getOptionUsing(\Closure $closure)
 * @method static \Illuminate\Support\Collection getOptions(\Lunar\Models\Contracts\Cart $cart)
 * @method static \Lunar\DataTypes\ShippingOption|null getOption(\Lunar\Models\Contracts\Cart $cart, string $identifier)
 * @method static \Lunar\DataTypes\ShippingOption|null getShippingOption(\Lunar\Models\Contracts\Cart $cart)
 *
 * @see \Lunar\Base\ShippingManifest
 */
class ShippingManifest extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return ShippingManifestInterface::class;
    }
}
