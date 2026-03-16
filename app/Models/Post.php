<?php

namespace App\Models;

use App\Base\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'tags',
        'status',
        'published_at',
        'views_count',
        'read_time_minutes',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'json',
            'status' => PostStatus::class,
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'read_time_minutes' => 'integer',
        ];
    }

    public function author()
    {
        return $this->belongsTo(Staff::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
}
