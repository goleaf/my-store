<?php

namespace App\Http\Requests\Support\Fields;

class NumericFieldRequest extends ConfiguredFieldRequest
{
    protected int|float|null $min = null;

    protected int|float|null $max = null;

    public function min(int|float|null $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int|float|null $max): static
    {
        $this->max = $max;

        return $this;
    }

    protected function baseRules(): array
    {
        return array_values(array_filter([
            'numeric',
            filled($this->min) ? 'min:' . $this->min : null,
            filled($this->max) ? 'max:' . $this->max : null,
        ]));
    }
}
