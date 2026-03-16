<?php

namespace App\Http\Requests\Filament\System;

use App\Http\Requests\BaseRequest;
use App\Models\SiteSetting;
use Illuminate\Validation\Rule;

class SiteSettingRequest extends BaseRequest
{
    protected ?SiteSetting $record = null;

    public function forRecord(?SiteSetting $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function rules(): array
    {
        $keyRule = Rule::unique(SiteSetting::class, 'key');

        if ($this->record) {
            $keyRule->ignore($this->record);
        }

        return [
            'key' => ['required', 'string', 'max:100', $keyRule],
            'value' => ['nullable', 'string'],
            'group' => ['required', 'string', 'max:50'],
            'type' => ['required', 'string', Rule::in(['text', 'boolean', 'json', 'image']), 'max:20'],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
