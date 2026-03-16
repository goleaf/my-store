<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\BaseRequest;

class UpdatePasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ];
    }
}
