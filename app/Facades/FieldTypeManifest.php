<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\FieldTypeManifestInterface;

/**
 * @method static void add(string $classname)
 * @method static \Illuminate\Support\Collection getTypes()
 *
 * @see \App\Store\Base\FieldTypeManifest
 */
class FieldTypeManifest extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return FieldTypeManifestInterface::class;
    }
}
