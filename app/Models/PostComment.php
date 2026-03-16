<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'parent_id',
        'customer_id',
        'guest_name',
        'guest_email',
        'body',
        'is_approved',
        'is_flagged',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_flagged' => 'boolean',
        ];
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }
}
