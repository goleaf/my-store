<?php

namespace App\Database\Factories;

use Illuminate\Support\Str;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\FieldTypes\Text;

class AttributeFactory extends BaseFactory
{
    private static $position = 1;

    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'attribute_group_id' => AttributeGroup::factory(),
            'attribute_type' => 'product',
            'position' => self::$position++,
            'name' => [
                'en' => $this->faker->name(),
            ],
            'description' => [
                'en' => Str::limit($this->faker->text(), 100),
            ],
            'handle' => Str::slug($this->faker->name()),
            'section' => $this->faker->name(),
            'type' => Text::class,
            'required' => false,
            'default_value' => '',
            'configuration' => [
                'options' => [
                    $this->faker->name(),
                    $this->faker->name(),
                    $this->faker->name(),
                ],
            ],
            'system' => false,
        ];
    }
}
