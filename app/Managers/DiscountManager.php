<?php

namespace App\Managers;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use App\Base\DataTransferObjects\CartDiscount;
use App\Base\DiscountManagerInterface;
use App\Base\Validation\CouponValidator;
use App\DiscountTypes\AmountOff;
use App\DiscountTypes\BuyXGetY;
use App\Models\Cart;
use App\Models\Channel;
use App\Models\CustomerGroup;
use App\Models\Discount;
use App\Models\Contracts;

class DiscountManager implements DiscountManagerInterface
{
    /**
     * The current channels.
     *
     * @var null|Collection<Channel>
     */
    protected ?Collection $channels = null;

    /**
     * The current customer groups
     *
     * @var null|Collection<CustomerGroup>
     */
    protected ?Collection $customerGroups = null;

    /**
     * The available discounts
     */
    protected ?Collection $discounts = null;

    /**
     * The available discount types
     *
     * @var array
     */
    protected $types = [
        AmountOff::class,
        BuyXGetY::class,
    ];

    /**
     * The applied discounts.
     */
    protected Collection $applied;

    /**
     * Instantiate the class.
     */
    public function __construct()
    {
        $this->applied = collect();
        $this->channels = collect();
        $this->customerGroups = collect();
    }

    /**
     * Set a single channel or a collection.
     */
    public function channel(Contracts\Channel|iterable $channel): self
    {
        $channels = collect(
            ! is_iterable($channel) ? [$channel] : $channel
        );

        if ($nonChannel = $channels->filter(fn ($channel) => ! $channel instanceof Contracts\Channel)->first()) {
            throw new InvalidArgumentException(
                __('store::exceptions.discounts.invalid_type', [
                    'expected' => Contracts\Channel::class,
                    'actual' => $nonChannel->getMorphClass(),
                ])
            );
        }

        $this->channels = $channels;

        return $this;
    }

    /**
     * Set a single customer group or a collection.
     */
    public function customerGroup(Contracts\CustomerGroup|iterable $customerGroups): self
    {
        $customerGroups = collect(
            ! is_iterable($customerGroups) ? [$customerGroups] : $customerGroups
        );

        if ($nonGroup = $customerGroups->filter(fn ($channel) => ! $channel instanceof Contracts\CustomerGroup)->first()) {
            throw new InvalidArgumentException(
                __('store::exceptions.discounts.invalid_type', [
                    'expected' => Contracts\CustomerGroup::class,
                    'actual' => $nonGroup->getMorphClass(),
                ])
            );
        }
        $this->customerGroups = $customerGroups;

        return $this;
    }

    /**
     * Return the applied channels.
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * Returns the available discounts.
     */
    public function getDiscounts(?Cart $cart = null): Collection
    {
        if ($this->channels->isEmpty() && $defaultChannel = Channel::getDefault()) {
            $this->channel($defaultChannel);
        }

        if ($cart && $customerGroups = $cart->customer?->customerGroups) {
            $this->customerGroup($customerGroups);
        }

        if ($this->customerGroups->isEmpty() && $defaultGroup = CustomerGroup::getDefault()) {
            $this->customerGroup($defaultGroup);
        }

        return Discount::active()
            ->usable()
            ->channel($this->channels)
            ->customerGroup($this->customerGroups)
            ->with([
                'discountables',
            ])
            ->when(
                $cart,
                function ($query, $value) {
                    return $query->where(function ($query) use ($value) {

                        return $query->where(fn ($query) => $query->products(
                            $value->lines->pluck('purchasable.product_id')->filter()->values(),
                            ['condition', 'limitation']
                        )
                        )
                            ->orWhere(fn ($query) => $query->productVariants(
                                $value->lines->pluck('purchasable.id')->filter()->values(),
                                ['condition', 'limitation']
                            )
                            )
                            ->orWhere(fn ($query) => $query->collections(
                                $value->lines->map(fn ($line) => $line->purchasable?->product?->collections?->pluck('id'))->flatten()->filter()->values(),
                                ['condition']
                            )
                            )
                            ->orWhere(fn ($query) => $query->brands(
                                $value->lines->map(fn ($line) => $line->purchasable?->product?->brand_id)->flatten()->filter()->values(),
                                ['condition']
                            )
                            );
                    });
                }
            )
            ->when(
                $cart?->coupon_code,
                function ($query, $value) {
                    return $query->where(function ($query) use ($value) {
                        $query->where('coupon', $value)
                            ->orWhereNull('coupon')
                            ->orWhere('coupon', '');
                    });
                },
                fn ($query, $value) => $query->whereNull('coupon')->orWhere('coupon', '')
            )->orderBy('priority', 'desc')
            ->orderBy('id')
            ->get();
    }

    /**
     * Return the applied customer groups.
     */
    public function getCustomerGroups(): Collection
    {
        return $this->customerGroups;
    }

    public function addType($classname): self
    {
        $this->types[] = $classname;

        return $this;
    }

    public function getTypes(): Collection
    {
        return collect($this->types)->map(function ($class) {
            return app($class);
        });
    }

    public function addApplied(CartDiscount $cartDiscount): self
    {
        $this->applied->push($cartDiscount);

        return $this;
    }

    public function getApplied(): Collection
    {
        return $this->applied;
    }

    public function apply(Contracts\Cart $cart): Contracts\Cart
    {
        if (! $this->discounts || $this->discounts?->isEmpty()) {
            $this->discounts = $this->getDiscounts($cart);
        }

        foreach ($this->discounts as $discount) {
            $cart = $discount->getType()->apply($cart);
        }

        return $cart;
    }

    public function resetDiscounts(): self
    {
        $this->discounts = null;

        return $this;
    }

    public function validateCoupon(string $coupon): bool
    {
        return app(
            config('store.discounts.coupon_validator', CouponValidator::class)
        )->validate($coupon);
    }
}
