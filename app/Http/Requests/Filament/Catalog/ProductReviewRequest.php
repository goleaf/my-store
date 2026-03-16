<?php

namespace App\Http\Requests\Filament\Catalog;

use App\Http\Requests\BaseRequest;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Validation\Rule;

class ProductReviewRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', Rule::exists((new Product)->getTable(), 'id')],
            'customer_id' => ['required', 'integer', Rule::exists((new Customer)->getTable(), 'id')],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_flavor' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_value' => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_scent' => ['nullable', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'images' => ['nullable', 'string'],
            'helpful_count' => ['required', 'integer', 'min:0'],
            'is_verified_purchase' => ['required', 'boolean'],
            'is_approved' => ['required', 'boolean'],
            'is_flagged' => ['required', 'boolean'],
            'admin_reply' => ['nullable', 'string'],
            'admin_replied_at' => ['nullable', 'date'],
        ];
    }
}
