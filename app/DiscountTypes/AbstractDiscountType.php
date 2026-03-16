<?php

namespace App\DiscountTypes;

use Illuminate\Support\Collection;
use App\Base\DiscountTypeInterface;
use App\Base\ValueObjects\Cart\DiscountBreakdown;
use App\Models\Customer;
use App\Models\Contracts\Cart;
use App\Models\Contracts\Discount;

abstract class AbstractDiscountType implements DiscountTypeInterface
{
    /**
     * The instance of the discount.
     */
    public Discount $discount;

    /**
     * Set the data for the discount to user.
     *
     * @param  array  $data
     */
    public function with(Discount $discount): self
    {
        /** @var \App\Models\Discount $discount */
        $this->discount = $discount;

        return $this;
    }

    /**
     * Mark a discount as used
     */
    public function markAsUsed(Cart $cart): self
    {
        /** @var \App\Models\Cart $cart */
        $this->discount->uses = $this->discount->uses + 1;

        if ($customer = $cart->customer) {
            $this->discount->customers()->syncWithoutDetaching([$customer->getKey()]);
        }

        return $this;
    }

    /**
     * Return the eligible lines for the discount.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getEligibleLines(Cart $cart): Collection
    {
        /** @var \App\Models\Cart $cart */
        return $cart->lines;
    }

    /**
     * Check if discount's conditions met.
     */
    protected function checkDiscountConditions(Cart $cart): bool
    {
        /** @var \App\Models\Cart $cart */
        $data = $this->discount->data;

        $customerIds = $this->discount->customers->pluck('id');

        if ((! $customerIds->isEmpty() && ! $cart->customer) || (! $customerIds->isEmpty() && ! $customerIds->contains($cart->customer_id))) {
            return false;
        }

        $cartCoupon = strtoupper($cart->coupon_code ?? '');
        $conditionCoupon = strtoupper($this->discount->coupon ?? '');

        $validCoupon = filled($conditionCoupon) ? ($cartCoupon === $conditionCoupon) : true;

        $minSpend = (int) ($data['min_prices'][$cart->currency->code] ?? 0) / (int) $cart->currency->factor;
        $minSpend = (int) bcmul($minSpend, $cart->currency->factor);

        $lines = $this->getEligibleLines($cart);
        $validMinSpend = $minSpend ? $minSpend < $lines->sum('subTotal.value') : true;

        $validMaxUses = $this->discount->max_uses ? $this->discount->uses < $this->discount->max_uses : true;

        if ($validMaxUses && $this->discount->max_uses_per_user) {
            $validMaxUses = $cart->customer && ($this->usesByCustomer($cart->customer) < $this->discount->max_uses_per_user);
        }

        return $validCoupon && $validMinSpend && $validMaxUses;
    }

    /**
     * Check if discount's conditions met.
     *
     * @param  Store\Base\ValueObjects\Cart\DiscountBreakdown  $breakdown
     * @return self
     */
    protected function addDiscountBreakdown(Cart $cart, DiscountBreakdown $breakdown)
    {
        /** @var \App\Models\Cart $cart */
        if (! $cart->discountBreakdown) {
            $cart->discountBreakdown = collect();
        }
        $cart->discountBreakdown->push($breakdown);

        return $this;
    }

    /**
     */
    protected function usesByCustomer(Customer $customer): int
    {
        return $this->discount->customers()
            ->where('customer_id', $customer->getKey())
            ->count();
    }
}
