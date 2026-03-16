<?php

namespace App\Store\Facades;

use Illuminate\Support\Facades\Facade;
use App\Store\Base\ModelManifestInterface;

/**
 * @method static void register()
 * @method static void addDirectory(string $dir)
 * @method static void add(string $interfaceClass, string $modelClass)
 * @method static void replace(string $interfaceClass, string $modelClass)
 * @method static string|null get(string $interfaceClass)
 * @method static string guessContractClass(string $modelClass)
 * @method static string guessModelClass(string $modelContract)
 * @method static string|null findStoreModel(\App\Store\Base\BaseModel|string $model)
 * @method static bool isStoreModel(\App\Store\Base\BaseModel|string $model)
 * @method static void morphMap()
 * @method static string getMorphMapKey(void $className)
 *
 * @see \App\Store\Base\ModelManifest
 */
class ModelManifest extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return ModelManifestInterface::class;
    }
}
