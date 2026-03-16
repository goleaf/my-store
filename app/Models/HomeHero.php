<?php

namespace App\Models;

use App\Base\Traits\HasTranslations;
use Database\Factories\HomeHeroFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeHero extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'store_home_heroes';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'link',
        'button_text',
        'image',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'title' => AsArrayObject::class,
            'subtitle' => AsArrayObject::class,
            'description' => AsArrayObject::class,
            'link' => AsArrayObject::class,
            'button_text' => AsArrayObject::class,
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    protected static function newFactory(): HomeHeroFactory
    {
        return HomeHeroFactory::new();
    }
}
