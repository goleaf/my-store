<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ValidMorphedRecord implements Rule, DataAwareRule
{
    protected array $data = [];

    public function __construct(
        protected string $recordTypeField = 'record_type'
    ) {
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        if (! is_numeric($value)) {
            return true;
        }

        $recordType = $this->data[$this->recordTypeField] ?? null;
        if (! is_string($recordType)) {
            return true;
        }

        $modelClass = Relation::getMorphedModel($recordType);
        if (! is_string($modelClass) || ! is_subclass_of($modelClass, Model::class)) {
            return true;
        }

        return $modelClass::query()->whereKey((int) $value)->exists();
    }

    public function message(): string
    {
        return 'The :attribute is invalid.';
    }
}
