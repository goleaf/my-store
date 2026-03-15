<?php

namespace App\Store\Base\Validation;

use App\Store\DiscountTypes\AmountOff;
use App\Store\DiscountTypes\BuyXGetY;
use App\Store\Models\Discount;

class CouponValidator implements CouponValidatorInterface
{
    public function validate(string $coupon): bool
    {
        return Discount::whereIn('type', [AmountOff::class, BuyXGetY::class])
            ->active()
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhereRaw('uses < max_uses');
            })->where('coupon', '=', strtoupper($coupon))->exists();
    }
}
