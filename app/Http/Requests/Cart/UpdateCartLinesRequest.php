<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseRequest;

class UpdateCartLinesRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'lines.*.quantity' => ['required', 'numeric', 'min:1', 'max:10000'],
        ];
    }
}
