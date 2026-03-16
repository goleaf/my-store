<?php

namespace App\Http\Requests\Filament\Store;

use App\Http\Requests\BaseRequest;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseRequest
{
    protected ?Store $record = null;

    public function forRecord(?Store $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function rules(): array
    {
        $slugRule = Rule::unique(Store::class, 'slug');

        if ($this->record) {
            $slugRule->ignore($this->record);
        }

        return [
            'owner_id' => ['required', Rule::exists((new Customer)->getTable(), 'id')],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', $slugRule],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image'],
            'banner' => ['nullable', 'image'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'commission_rate' => ['nullable', 'numeric', 'min:0'],
            'rating_avg' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'total_reviews' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
