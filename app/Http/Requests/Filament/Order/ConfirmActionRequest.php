<?php

namespace App\Http\Requests\Filament\Order;

use App\Http\Requests\BaseRequest;

class ConfirmActionRequest extends BaseRequest
{
    protected string $field = 'confirm';

    public function forField(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function rules(): array
    {
        return [
            $this->field => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->field}.accepted" => __('admin::order.form.confirm.alert'),
        ];
    }
}
