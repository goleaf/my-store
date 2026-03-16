<?php

namespace App\Models;

use App\Base\Enums\HomeSectionType;
use Database\Factories\HomeSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

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
        'type' => HomeSectionType::class,
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    protected static function newFactory(): HomeSectionFactory
    {
        return HomeSectionFactory::new();
    }
}
