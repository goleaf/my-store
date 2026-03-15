<?php

namespace App\Store\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Store\Base\BaseModel;
use App\Store\Database\Factories\DiscountableFactory;

class DiscountCollection extends BaseModel implements Contracts\DiscountCollection
{
    use HasFactory;

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return DiscountableFactory::new();
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::modelClass());
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::modelClass());
    }
}
