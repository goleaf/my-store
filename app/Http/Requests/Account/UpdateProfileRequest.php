<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseRequest
{
    protected ?int $userId = null;

    public function forUser(?int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('store_customers', 'email')->ignore($this->userId)],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
