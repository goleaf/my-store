<?php

namespace App\Pipelines\CartLine;

use Closure;
use App\DataTypes\Price;
use App\Facades\Pricing;
use Spatie\LaravelBlink\BlinkFacade;
use App\Models\Contracts\CartLine;

class GetUnitPrice
{
    /**
     * Called just before cart totals are calculated.
     *
     * @param  Closure(\App\Models\Contracts\CartLine): mixed  $next
     * @return Closure
     */
    public function handle(CartLine $cartLine, Closure $next)
    {
        /** @var \App\Models\CartLine $cart */
        $purchasable = $cartLine->purchasable;
        $cart = $cartLine->cart;

        if ($customer = $cart->customer) {
            $customerGroups = $customer->customerGroups;
        } else {
            $customerGroups = collect();
        }

        $currency = BlinkFacade::once('currency_'.$cart->currency_id, function () use ($cart) {
            return $cart->currency;
        });

        $priceResponse = Pricing::currency($currency)
            ->qty($cartLine->quantity)
            ->currency($cart->currency)
            ->customerGroups($customerGroups)
            ->for($purchasable)
            ->get();

        $cartLine->unitPrice = new Price(
            $priceResponse->matched->price->value,
            $cart->currency,
            $purchasable->getUnitQuantity()
        );

        $cartLine->unitPriceInclTax = new Price(
            $priceResponse->matched->priceIncTax()->value,
            $cart->currency,
            $purchasable->getUnitQuantity()
        );

        return $next($cartLine);
    }
}
