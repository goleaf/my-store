<?php

namespace App\Base\Enums\Concerns;

trait TranslatesEnumLabels
{
    abstract public function translationKey(): string;

    public static function resolve(self|string|null $value): ?static
    {
        if ($value instanceof static) {
            return $value;
        }

        if (! is_string($value)) {
            return null;
        }

        return static::tryFrom($value);
    }

    public function label(): string
    {
        return __($this->translationKey());
    }

    public static function labelFor(self|string|null $value): ?string
    {
        return static::resolve($value)?->label();
    }

    public static function options(): array
    {
        return collect(static::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->all();
    }
}
