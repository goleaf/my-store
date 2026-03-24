<?php

namespace App\Http\Requests\System;

use App\Http\Requests\BaseRequest;
use App\Rules\ValidMorphedRecord;
use Closure;
use Illuminate\Database\Eloquent\Relations\Relation;

class DownloadPdfRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'record' => [
                'required',
                'integer',
                'min:1',
                new ValidMorphedRecord('record_type'),
            ],
            'record_type' => [
                'required',
                'string',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! is_string($value) || Relation::getMorphedModel($value) === null) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],
            'view' => [
                'required',
                'string',
                'starts_with:admin::pdf.',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (! is_string($value) || ! view()->exists($value)) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],
        ];
    }
}
