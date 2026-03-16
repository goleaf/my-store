<?php

namespace App\Http\Requests\Filament\Pricing;

use App\Http\Requests\BaseRequest;
use App\Models\Price;
use Closure;
use Illuminate\Database\Eloquent\Model;

class MinQuantityRequest extends BaseRequest
{
    protected ?Model $owner = null;

    protected ?Price $record = null;

    protected ?int $currencyId = null;

    protected ?int $customerGroupId = null;

    public function forContext(Model $owner, ?Price $record, mixed $currencyId, mixed $customerGroupId): static
    {
        $this->owner = $owner;
        $this->record = $record;
        $this->currencyId = filled($currencyId) ? (int) $currencyId : null;
        $this->customerGroupId = filled($customerGroupId) ? (int) $customerGroupId : null;

        return $this;
    }

    public function rules(): array
    {
        return [
            'min_quantity' => [
                'required',
                'numeric',
                'min:2',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! $this->owner || blank($this->currencyId) || blank($value)) {
                        return;
                    }

                    $exists = Price::query()
                        ->when(filled($this->record), fn ($query) => $query->where('id', '!=', $this->record->id))
                        ->when(
                            blank($this->customerGroupId),
                            fn ($query) => $query->whereNull('customer_group_id'),
                            fn ($query) => $query->where('customer_group_id', $this->customerGroupId)
                        )
                        ->where('currency_id', $this->currencyId)
                        ->where('priceable_type', $this->owner->getMorphClass())
                        ->where('priceable_id', $this->owner->getKey())
                        ->where('min_quantity', $value)
                        ->exists();

                    if ($exists) {
                        $fail(__('admin::relationmanagers.pricing.form.min_quantity.validation.unique'));
                    }
                },
            ],
        ];
    }
}
