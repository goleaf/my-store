<?php

namespace App\Http\Requests\Checkout;

use App\Http\Requests\BaseRequest;

class SaveAddressRequest extends BaseRequest
{
    protected ?string $prefix = null;

    protected bool $requiresContactEmail = false;

    public function withPrefix(?string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function requireContactEmail(bool $requiresContactEmail = true): static
    {
        $this->requiresContactEmail = $requiresContactEmail;

        return $this;
    }

    public function rules(): array
    {
        return $this->prefixRules([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'line_one' => ['required'],
            'country_id' => ['required'],
            'city' => ['required'],
            'postcode' => ['required'],
            'company_name' => ['nullable'],
            'line_two' => ['nullable'],
            'line_three' => ['nullable'],
            'state' => ['nullable'],
            'delivery_instructions' => ['nullable'],
            'contact_email' => $this->requiresContactEmail ? ['required', 'email'] : ['nullable', 'email'],
            'contact_phone' => ['nullable'],
        ], $this->prefix);
    }

    protected function validationPrefix(): ?string
    {
        return $this->prefix;
    }
}
