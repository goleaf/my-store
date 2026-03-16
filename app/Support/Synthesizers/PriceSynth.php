<?php

namespace App\Support\Synthesizers;

use App\DataTypes\Price;
use App\Models\Currency;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

final class PriceSynth extends Synth
{
    public static $key = 'store_price';

    public static function match($target)
    {
        return $target instanceof Price;
    }

    public function dehydrate($target)
    {
        return [[
            'value' => $target->value,
            'currency' => $target->currency->code,
            'unitQty' => $target->unitQty,
        ], []];
    }

    public function hydrate($value)
    {
        $currency = Currency::where('code', $value['currency'])->first();

        return new Price($value['value'], $currency, $value['unitQty']);
    }
}
