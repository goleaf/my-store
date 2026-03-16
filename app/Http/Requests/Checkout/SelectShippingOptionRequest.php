<?php

namespace App\Http\Requests\Checkout;

use App\Http\Requests\BaseRequest;

class SelectShippingOptionRequest extends BaseRequest
{
    protected string $field = 'chosenOption';

    public function forField(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function rules(): array
    {
        return [
            $this->field => ['required'],
        ];
    }
}
