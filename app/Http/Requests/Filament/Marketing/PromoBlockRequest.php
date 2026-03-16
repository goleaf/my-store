<?php

namespace App\Http\Requests\Filament\Marketing;

use App\Http\Requests\BaseRequest;

class PromoBlockRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'badge_text' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image'],
            'bg_color' => ['nullable', 'string', 'max:20'],
            'position' => ['required', 'string', 'max:50'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
