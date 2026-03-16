<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\FieldTypeManifestInterface;

/**
 * @method static void add(string $classname)
 * @method static \Illuminate\Support\Collection getTypes()
 *
 * @see \App\Base\FieldTypeManifest
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
