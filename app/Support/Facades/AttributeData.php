<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Filament\Forms\Components\Component getFilamentComponent(\App\Store\Models\Attribute $attribute)
 * @method static \App\Store\Admin\Support\Forms\AttributeData registerFieldType(string $coreFieldType, string $panelFieldType)
 * @method static \Illuminate\Support\Collection getFieldTypes()
 * @method static array getConfigurationFields(string|null $type = null)
 * @method static void synthesizeLivewireProperties()
 *
 * @see \App\Store\Admin\Support\Forms\AttributeData
 */
class AttributeData extends Facade
{
    /**
     * Return the facade class reference.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'admin-attribute-data';
    }
}
