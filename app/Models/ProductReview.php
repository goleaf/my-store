<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_id',
        'rating',
        'rating_flavor',
        'rating_value',
        'rating_scent',
        'title',
        'body',
        'images',
        'helpful_count',
        'is_verified_purchase',
        'is_approved',
        'is_flagged',
        'admin_reply',
        'admin_replied_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'images' => 'json',
            'is_verified_purchase' => 'boolean',
            'is_approved' => 'boolean',
            'is_flagged' => 'boolean',
            'admin_replied_at' => 'datetime',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function helpfulVotes()
    {
        return $this->hasMany(ReviewHelpfulVote::class, 'review_id');
    }
}
