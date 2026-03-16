<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Base\BaseModel;
use App\Base\Traits\HasMacros;
use App\Database\Factories\CountryFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $iso3
 * @property ?string $iso2
 * @property string $phonecode
 * @property ?string $capital
 * @property string $currency
 * @property ?string $native
 * @property string $emoji
 * @property string $emoji_u
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Country extends BaseModel implements Contracts\Country
{
    use HasFactory;
    use HasMacros;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CountryFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    public function states(): HasMany
    {
        return $this->hasMany(State::modelClass());
    }
}
