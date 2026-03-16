<?php

namespace App\Validation\Cart;

use App\Http\Requests\Cart\OrderAddressRequest;
use App\Validation\BaseValidator;

class ValidateCartForOrderCreation extends BaseValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        $cart = $this->parameters['cart'];

        // Does this cart already have an order?
        if ($cart->completedOrder) {
            return $this->fail('cart', __('store::exceptions.carts.order_exists'));
        }

        // Do we have a billing address?
        if (! $cart->billingAddress) {
            return $this->fail('cart', __('store::exceptions.carts.billing_missing'));
        }

        $billingValidator = app(OrderAddressRequest::class)->makeValidator(
            $cart->billingAddress->toArray()
        );

        if ($billingValidator->fails()) {
            return $this->fail('cart', $billingValidator->errors()->getMessages());
        }

        if ($cart->isShippable()) {
            // Do we have a shipping option applied?
            if (! $shippingOption = $cart->getShippingOption()) {
                return $this->fail('cart', __('store::exceptions.carts.shipping_option_missing'));
            }

            // Is this cart going to be shipped and if so, does it have a shipping address?
            if (! $shippingOption->collect) {
                if (! $cart->shippingAddress) {
                    return $this->fail('cart', __('store::exceptions.carts.shipping_missing'));
                }

                $shippingValidator = app(OrderAddressRequest::class)->makeValidator(
                    $cart->shippingAddress->toArray()
                );

                if ($shippingValidator->fails()) {
                    return $this->fail('cart', $shippingValidator->errors()->getMessages());
                }
            }
        }

        return $this->pass();
    }
}
