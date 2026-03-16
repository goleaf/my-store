<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Base\BaseModel;
use App\Base\Traits\HasMacros;
use App\Base\Traits\HasMedia;
use App\Base\Traits\HasTranslations;
use App\Base\Traits\LogsActivity;
use App\Base\Traits\Searchable;
use App\Database\Factories\ProductOptionFactory;
use Spatie\MediaLibrary;

/**
 * @property int $id
 * @property AsArrayObject $name
 * @property ?AsArrayObject $label
 * @property int $position
 * @property ?string $handle
 * @property bool $shared
 * @property ?AsArrayObject $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class ProductOption extends BaseModel implements Contracts\ProductOption, MediaLibrary\HasMedia
{
    use HasFactory;
    use HasMacros;
    use HasMedia;
    use HasTranslations;
    use LogsActivity;
    use Searchable;

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => AsArrayObject::class,
        'label' => AsArrayObject::class,
        'shared' => 'boolean',
        'meta' => AsArrayObject::class,
    ];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ProductOptionFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeShared(Builder $builder): Builder
    {
        return $builder->where('shared', '=', true);
    }

    public function scopeExclusive(Builder $builder): Builder
    {
        return $builder->where('shared', '=', false);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::modelClass())->orderBy('position');
    }

    public function products(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            Product::modelClass(),
            "{$prefix}product_product_option"
        )->withPivot(['position'])->orderByPivot('position');
    }
}
