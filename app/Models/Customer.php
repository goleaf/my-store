<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Base\BaseModel;
use App\Base\Casts\AsAttributeData;
use App\Base\Traits\HasAttributes;
use App\Base\Traits\HasMacros;
use App\Base\Traits\HasPersonalDetails;
use App\Base\Traits\HasTranslations;
use App\Base\Traits\LogsActivity;
use App\Base\Traits\Searchable;
use App\Database\Factories\CustomerFactory;

/**
 * @property int $id
 * @property ?string $title
 * @property string $first_name
 * @property string $last_name
 * @property ?string $company_name
 * @property ?string $tax_identifier
 * @property ?string $account_ref
 * @property ?array $attribute_data
 * @property ?array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Customer extends BaseModel implements Contracts\Customer
{
    use HasAttributes;
    use HasFactory;
    use HasMacros;
    use HasPersonalDetails;
    use HasTranslations;
    use LogsActivity;
    use Searchable;

    /**
     * Define the guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'meta' => AsArrayObject::class,
    ];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CustomerFactory::new();
    }

    public function customerGroups(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::modelClass(),
            "{$prefix}customer_customer_group"
        )->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            config('auth.providers.users.model'),
            "{$prefix}customer_user"
        )->withTimestamps();
    }

    public function discounts(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            Discount::modelClass(),
            "{$prefix}customer_discount"
        )->withTimestamps();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::modelClass());
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::modelClass());
    }

    public function mappedAttributes(): MorphToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->morphToMany(
            Attribute::modelClass(),
            'attributable',
            "{$prefix}attributables"
        )->withTimestamps();
    }
}
