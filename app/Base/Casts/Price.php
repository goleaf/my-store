<?php

namespace App\Base\Casts;

use App\Http\Requests\Support\PriceCastRequest;
use App\Models\Currency;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\DataTypes;

class Price implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return \App\DataTypes\Price
     */
    public function get($model, $key, $value, $attributes)
    {
        $currency = $model->currency ?: Currency::getDefault();

        if (! is_null($value)) {
            /**
             * Make it an integer based on currency requirements.
             */
            $value = preg_replace('/[^0-9]/', '', $value);
        }

        app(PriceCastRequest::class)
            ->forField($key)
            ->validatePayload([
                $key => $value,
            ]);

        return new DataTypes\Price(
            (int) $value,
            $currency,
            $model->priceable->unit_quantity ?? $model->unit_quantity ?? 1,
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \App\DataTypes\Price  $value
     * @param  array  $attributes
     * @return array
     */
    public function set($model, $key, $value, $attributes)
    {
        return [
            $key => $value,
        ];
    }
}
