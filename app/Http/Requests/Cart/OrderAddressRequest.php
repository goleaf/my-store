<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseRequest;

class OrderAddressRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'country_id' => ['required'],
            'first_name' => ['required'],
            'line_one' => ['required'],
            'city' => ['required'],
            'postcode' => ['required'],
        ];
    }
}
