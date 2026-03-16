<?php

namespace App\Http\Requests\Filament\Sales;

use App\Http\Requests\BaseRequest;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class CustomerRequest extends BaseRequest
{
    protected ?Model $record = null;

    public function forRecord(?Model $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function rules(): array
    {
        $emailRule = Rule::unique((new Customer)->getTable(), 'email');

        if ($this->record) {
            $emailRule->ignore($this->record);
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'phone' => ['nullable', 'string', 'max:50'],
            'account_ref' => ['nullable', 'string', 'max:255'],
            'tax_identifier' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(['active', 'banned', 'unverified'])],
            'locale' => ['nullable', 'string', 'max:10'],
            'customerGroups' => ['nullable', 'array'],
        ];
    }
}
