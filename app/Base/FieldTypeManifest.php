<?php

namespace App\Base;

use App\Exceptions\FieldTypes\FieldTypeMissingException;
use App\Exceptions\FieldTypes\InvalidFieldTypeException;
use App\FieldTypes\Dropdown;
use App\FieldTypes\File;
use App\FieldTypes\ListField;
use App\FieldTypes\Number;
use App\FieldTypes\Text;
use App\FieldTypes\Toggle;
use App\FieldTypes\TranslatedText;
use App\FieldTypes\YouTube;

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
