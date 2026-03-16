<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\ShippingManifestInterface;

/**
 * @method static void addOption(\App\Store\DataTypes\ShippingOption $option)
 * @method static void addOptions(\Illuminate\Support\Collection $options)
 * @method static void clearOptions()
 * @method static \App\Store\Base\ShippingManifest getOptionUsing(\Closure $closure)
 * @method static \Illuminate\Support\Collection getOptions(\App\Store\Models\Contracts\Cart $cart)
 * @method static \App\Store\DataTypes\ShippingOption|null getOption(\App\Store\Models\Contracts\Cart $cart, string $identifier)
 * @method static \App\Store\DataTypes\ShippingOption|null getShippingOption(\App\Store\Models\Contracts\Cart $cart)
 *
 * @see \App\Store\Base\ShippingManifest
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
