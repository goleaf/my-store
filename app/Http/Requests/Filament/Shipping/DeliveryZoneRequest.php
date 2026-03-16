<?php

namespace App\Http\Requests\Filament\Shipping;

use App\Http\Requests\BaseRequest;

class DeliveryZoneRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'min_order' => ['nullable', 'numeric', 'min:0'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
