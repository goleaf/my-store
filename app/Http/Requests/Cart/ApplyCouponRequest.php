<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseRequest;

class ApplyCouponRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'couponCode' => ['nullable', 'string', 'max:255'],
        ];
    }
}
