<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\BaseRequest;

class SaveAddressRequest extends BaseRequest
{
    protected function validationPrefix(): ?string
    {
        return 'address';
    }

    public function rules(): array
    {
        return $this->prefixRules([
            'title' => ['nullable', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'tax_identifier' => ['nullable', 'string', 'max:255'],
            'line_one' => ['required', 'string', 'max:255'],
            'line_two' => ['nullable', 'string', 'max:255'],
            'line_three' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'country_id' => ['required', 'integer', 'exists:store_countries,id'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'shipping_default' => ['boolean'],
            'billing_default' => ['boolean'],
        ]);
    }
}
