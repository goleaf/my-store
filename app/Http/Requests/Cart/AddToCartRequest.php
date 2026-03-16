<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseRequest;

class AddToCartRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'numeric', 'min:1', 'max:10000'],
        ];
    }
}
