<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\ShippingManifestInterface;

/**
 * @method static void addOption(\App\DataTypes\ShippingOption $option)
 * @method static void addOptions(\Illuminate\Support\Collection $options)
 * @method static void clearOptions()
 * @method static \App\Base\ShippingManifest getOptionUsing(\Closure $closure)
 * @method static \Illuminate\Support\Collection getOptions(\App\Models\Contracts\Cart $cart)
 * @method static \App\DataTypes\ShippingOption|null getOption(\App\Models\Contracts\Cart $cart, string $identifier)
 * @method static \App\DataTypes\ShippingOption|null getShippingOption(\App\Models\Contracts\Cart $cart)
 *
 * @see \App\Base\ShippingManifest
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
