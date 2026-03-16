<?php

namespace App\Http\Requests\Support\Fields;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Arr;

class ConfiguredFieldRequest extends BaseRequest
{
    protected string $field = 'value';

    protected bool $isRequired = false;

    protected array $customRules = [];

    public function forField(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function required(bool $isRequired = true): static
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function withRules(string|array|null $rules): static
    {
        $this->customRules = Arr::wrap($rules);

        return $this;
    }

    public function mergeRules(string|array|null $rules): static
    {
        $this->customRules = [
            ...$this->customRules,
            ...Arr::wrap($rules),
        ];

        return $this;
    }

    public function rules(): array
    {
        $rules = [
            ...$this->presenceRules(),
            ...$this->baseRules(),
            ...$this->customRules,
        ];

        return [
            $this->field => array_values(array_filter($rules, fn (mixed $rule): bool => filled($rule))),
        ];
    }

    protected function baseRules(): array
    {
        return [];
    }

    protected function presenceRules(): array
    {
        $rules = [
            ...$this->baseRules(),
            ...$this->customRules,
        ];

        if ($this->containsRule($rules, 'required')) {
            return [];
        }

        return [$this->isRequired ? 'required' : 'nullable'];
    }

    protected function containsRule(array $rules, string $needle): bool
    {
        foreach ($rules as $rule) {
            if (! is_string($rule)) {
                continue;
            }

            if ($rule === $needle || str_starts_with($rule, "{$needle}:")) {
                return true;
            }
        }

        return false;
    }
}
