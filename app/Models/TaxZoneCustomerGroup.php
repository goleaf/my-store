<?php

namespace App\Store\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Store\Base\BaseModel;
use App\Store\Base\Traits\HasMacros;
use App\Store\Database\Factories\TaxZoneCustomerGroupFactory;

/**
 * @property int $id
 * @property ?int $tax_zone_id
 * @property ?int $customer_group_id
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class TaxZoneCustomerGroup extends BaseModel implements Contracts\TaxZoneCustomerGroup
{
    use HasFactory;
    use HasMacros;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return TaxZoneCustomerGroupFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Return the customer group relation.
     */
    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::modelClass());
    }

    /**
     * Return the tax zone relation.
     */
    public function taxZone(): BelongsTo
    {
        return $this->belongsTo(TaxZone::modelClass());
    }
}
