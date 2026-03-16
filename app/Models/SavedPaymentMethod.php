<?php

namespace App\Models;

use App\Base\Enums\SavedPaymentMethodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'stripe_customer_id',
        'stripe_payment_method_id',
        'last_four',
        'brand',
        'expiry_month',
        'expiry_year',
        'paypal_email',
        'payoneer_account_id',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'expiry_month' => 'integer',
            'expiry_year' => 'integer',
            'type' => SavedPaymentMethodType::class,
            'stripe_customer_id' => 'encrypted',
            'stripe_payment_method_id' => 'encrypted',
            'paypal_email' => 'encrypted',
            'payoneer_account_id' => 'encrypted',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
