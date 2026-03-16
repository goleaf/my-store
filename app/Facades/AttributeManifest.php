<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\AttributeManifestInterface;

/**
 * @method static void addType(void $type, void $key = null)
 * @method static \Illuminate\Support\Collection getTypes()
 * @method static void getType(void $key)
 * @method static \Illuminate\Support\Collection getSearchableAttributes(string $attributeType)
 *
 * @see \App\Store\Base\AttributeManifest
 */
class AttributeManifest extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return AttributeManifestInterface::class;
    }
}
