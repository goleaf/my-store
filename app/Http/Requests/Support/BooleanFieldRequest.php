<?php

namespace App\Http\Requests\Support;

use App\Http\Requests\Support\Fields\ConfiguredFieldRequest;

class BooleanFieldRequest extends ConfiguredFieldRequest
{
    protected function baseRules(): array
    {
        return ['boolean'];
    }
}
