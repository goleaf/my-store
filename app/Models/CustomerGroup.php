<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Base\BaseModel;
use App\Base\Casts\AsAttributeData;
use App\Base\Traits\HasAttributes;
use App\Base\Traits\HasDefaultRecord;
use App\Base\Traits\HasMacros;
use App\Base\Traits\LogsActivity;
use App\Database\Factories\CustomerGroupFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property bool $default
 * @property ?array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class CustomerGroup extends BaseModel implements Contracts\CustomerGroup
{
    use HasAttributes;
    use HasDefaultRecord;
    use HasFactory;
    use HasMacros;
    use LogsActivity;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CustomerGroupFactory::new();
    }

    public function customers(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            Customer::modelClass(),
            "{$prefix}customer_customer_group"
        )->withTimestamps();
    }

    /**
     * Return the discounts relationship.
     */
    public function discounts(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            Discount::modelClass(),
            "{$prefix}customer_group_discount"
        )->withTimestamps();
    }

    /**
     * Return the product relationship.
     */
    public function products(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            Product::modelClass(),
            "{$prefix}customer_group_product"
        )->withTimestamps();
    }

    public function collections(): BelongsToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->belongsToMany(
            Collection::modelClass(),
            "{$prefix}collection_customer_group"
        )->withTimestamps();
    }

    /**
     * Get the mapped attributes relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function mappedAttributes()
    {
        $prefix = config('store.database.table_prefix');

        return $this->morphToMany(
            Attribute::class,
            'attributable',
            "{$prefix}attributables"
        )->withTimestamps();
    }
}
