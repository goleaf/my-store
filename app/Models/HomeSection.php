<?php

namespace App\Store\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSection extends Model
{
    protected $table = 'store_home_sections';

    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'collection_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
