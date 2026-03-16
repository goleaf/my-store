<?php

namespace App\Models;

use App\Base\Enums\HomeBannerType;
use Database\Factories\HomeBannerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeBanner extends Model
{
    use HasFactory;

    protected $table = 'store_home_banners';

    protected $fillable = [
        'title',
        'subtitle',
        'link',
        'image',
        'type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'type' => HomeBannerType::class,
    ];

    protected static function newFactory(): HomeBannerFactory
    {
        return HomeBannerFactory::new();
    }
}
