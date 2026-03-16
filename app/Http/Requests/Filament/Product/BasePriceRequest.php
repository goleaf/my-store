<?php

namespace App\Http\Requests\Filament\Product;

use App\Http\Requests\BaseRequest;
use App\Models\Currency;

class BasePriceRequest extends BaseRequest
{
    protected string $field = 'base_price';

    protected int $factor = 100;

    protected int $decimalPlaces = 2;

    public function forCurrency(Currency $currency): static
    {
        $this->factor = $currency->factor;
        $this->decimalPlaces = $currency->decimal_places;

        return $this;
    }

    public function rules(): array
    {
        return [
            $this->field => [
                'required',
                'numeric',
                'min:' . (1 / $this->factor),
                "decimal:0,{$this->decimalPlaces}",
            ],
        ];
    }
}
