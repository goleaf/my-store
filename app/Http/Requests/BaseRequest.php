<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

abstract class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function validatePayload(array $payload): array
    {
        return $this->makeValidator($payload)->validate();
    }

    public function makeValidator(array $payload): Validation\Validator
    {
        return Validator::make($payload, $this->rules(), $this->messages(), $this->attributes());
    }

    public function fieldRules(string $field): array
    {
        return (array) ($this->rules()[$field] ?? []);
    }

    public function fieldHasRule(string $field, string $ruleName): bool
    {
        foreach ($this->fieldRules($field) as $rule) {
            if (! is_string($rule)) {
                continue;
            }

            if ($rule === $ruleName || str_starts_with($rule, "{$ruleName}:")) {
                return true;
            }
        }

        return false;
    }

    protected function prefixRules(array $rules, ?string $prefix = null): array
    {
        $prefix = $prefix ?? $this->validationPrefix();

        if (blank($prefix)) {
            return $rules;
        }

        return collect($rules)->mapWithKeys(function (mixed $rule, string $field) use ($prefix): array {
            return ["{$prefix}.{$field}" => $rule];
        })->all();
    }

    protected function prefixAttributes(array $attributes, ?string $prefix = null): array
    {
        $prefix = $prefix ?? $this->validationPrefix();

        if (blank($prefix)) {
            return $attributes;
        }

        return collect($attributes)->mapWithKeys(function (string $label, string $field) use ($prefix): array {
            return ["{$prefix}.{$field}" => $label];
        })->all();
    }

    protected function validationPrefix(): ?string
    {
        return null;
    }
}
