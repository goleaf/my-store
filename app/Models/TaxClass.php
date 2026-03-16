<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Base\BaseModel;
use App\Base\Traits\HasDefaultRecord;
use App\Base\Traits\HasMacros;
use App\Base\Traits\LogsActivity;
use App\Database\Factories\TaxClassFactory;

/**
 * @property int $id
 * @property string $name
 * @property bool $default
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class TaxClass extends BaseModel implements Contracts\TaxClass
{
    use HasDefaultRecord;
    use HasFactory;
    use HasMacros;
    use LogsActivity;

    public static function booted()
    {
        static::updated(function ($taxClass) {
            if ($taxClass->default) {
                TaxClass::whereDefault(true)->where('id', '!=', $taxClass->id)->update([
                    'default' => false,
                ]);
            }
        });

        static::created(function ($taxClass) {
            if ($taxClass->default) {
                TaxClass::whereDefault(true)->where('id', '!=', $taxClass->id)->update([
                    'default' => false,
                ]);
            }
        });
    }

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return TaxClassFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    public function taxRateAmounts(): HasMany
    {
        return $this->hasMany(TaxRateAmount::modelClass());
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::modelClass());
    }
}
