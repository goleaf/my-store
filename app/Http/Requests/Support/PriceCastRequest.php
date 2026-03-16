<?php

namespace App\Http\Requests\Support;

use App\Http\Requests\BaseRequest;

class PriceCastRequest extends BaseRequest
{
    protected string $field = 'price';

    public function forField(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function rules(): array
    {
        return [
            $this->field => ['nullable', 'numeric'],
        ];
    }
}
