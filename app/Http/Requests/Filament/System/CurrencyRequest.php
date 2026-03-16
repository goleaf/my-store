<?php

namespace App\Http\Requests\Filament\System;

use App\Http\Requests\BaseRequest;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class CurrencyRequest extends BaseRequest
{
    protected ?Model $record = null;

    public function forRecord(?Model $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function rules(): array
    {
        $codeRule = Rule::unique((new Currency)->getTable(), 'code');

        if ($this->record) {
            $codeRule->ignore($this->record);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'size:3', $codeRule],
            'exchange_rate' => ['required', 'numeric'],
            'decimal_places' => ['required', 'integer', 'min:0'],
            'enabled' => ['nullable', 'boolean'],
            'default' => ['nullable', 'boolean'],
            'sync_prices' => ['nullable', 'boolean'],
        ];
    }
}
