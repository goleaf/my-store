<?php

namespace App\Store\Base;

use App\Store\Exceptions\FieldTypes\FieldTypeMissingException;
use App\Store\Exceptions\FieldTypes\InvalidFieldTypeException;
use App\Store\FieldTypes\Dropdown;
use App\Store\FieldTypes\File;
use App\Store\FieldTypes\ListField;
use App\Store\FieldTypes\Number;
use App\Store\FieldTypes\Text;
use App\Store\FieldTypes\Toggle;
use App\Store\FieldTypes\TranslatedText;
use App\Store\FieldTypes\YouTube;

class FieldTypeManifest
{
    /**
     * The FieldTypes available in Store.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $fieldTypes;

    public function __construct()
    {
        $this->fieldTypes = collect([
            Dropdown::class,
            ListField::class,
            Number::class,
            Text::class,
            Toggle::class,
            TranslatedText::class,
            YouTube::class,
            File::class,
        ]);
    }

    /**
     * Add a FieldType into Store.
     *
     * @param  string  $classname
     * @return void
     */
    public function add($classname)
    {
        if (! class_exists($classname)) {
            throw new FieldTypeMissingException($classname);
        }

        if (! (app()->make($classname) instanceof FieldType)) {
            throw new InvalidFieldTypeException($classname);
        }

        $this->fieldTypes->push($classname);
    }

    /**
     * Return the fieldtypes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTypes()
    {
        return $this->fieldTypes->map(fn ($type) => app()->make($type));
    }
}
