<?php

namespace App\Http\Requests\Account;

use App\Base\Enums\SavedPaymentMethodType;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Enum;

class StorePaymentMethodRequest extends BaseRequest
{
    protected SavedPaymentMethodType $paymentType = SavedPaymentMethodType::Card;

    public function forPaymentType(SavedPaymentMethodType|string $paymentType): static
    {
        $this->paymentType = $paymentType instanceof SavedPaymentMethodType
            ? $paymentType
            : SavedPaymentMethodType::from($paymentType);

        return $this;
    }

    public function rules(): array
    {
        $rules = [
            'type' => ['required', new Enum(SavedPaymentMethodType::class)],
            'is_default' => ['boolean'],
        ];

        if ($this->paymentType === SavedPaymentMethodType::Card) {
            $rules['cardholder_name'] = ['required', 'string', 'max:255'];
            $rules['card_number'] = ['required', 'string', 'min:12', 'max:19'];
            $rules['expiry_month'] = ['required', 'integer', 'min:1', 'max:12'];
            $rules['expiry_year'] = ['required', 'integer', 'min:' . now()->year, 'max:' . (now()->year + 25)];
        }

        if ($this->paymentType === SavedPaymentMethodType::Paypal) {
            $rules['paypal_email'] = ['required', 'email', 'max:255'];
        }

        if ($this->paymentType === SavedPaymentMethodType::Payoneer) {
            $rules['payoneer_account_id'] = ['required', 'string', 'max:255'];
        }

        return $this->prefixRules($rules, 'method');
    }
}
