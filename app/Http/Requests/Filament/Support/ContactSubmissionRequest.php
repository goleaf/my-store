<?php

namespace App\Http\Requests\Filament\Support;

use App\Http\Requests\BaseRequest;

class ContactSubmissionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'ip_address' => ['nullable', 'ip'],
            'is_read' => ['required', 'boolean'],
            'replied_at' => ['nullable', 'date'],
        ];
    }
}
