<?php

namespace App\Shipping\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Base\BaseModel;
use App\Base\Purchasable;
use App\Base\Traits\HasPrices;
use App\Base\Traits\LogsActivity;
use App\DataTypes\ShippingOption;
use App\Models\Contracts\Cart;
use App\Models\TaxClass;
use App\Shipping\Database\Factories\ShippingRateFactory;
use App\Shipping\DataTransferObjects\ShippingOptionRequest;
use App\Models;

class ShippingRate extends BaseModel implements Contracts\ShippingRate, Purchasable
{
    use HasFactory;
    use HasPrices;
    use LogsActivity;

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    private ?TaxClass $resolvedTaxClass;

    protected static function booted()
    {
        self::deleting(function (self $shippingRate) {
            DB::beginTransaction();
            $shippingRate->prices()->delete();
            DB::commit();
        });
    }

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ShippingRateFactory::new();
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::modelClass());
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::modelClass());
    }

    public function getPrices(): Collection
    {
        return $this->prices;
    }

    /**
     * Return the unit quantity for the variant.
     */
    public function getUnitQuantity(): int
    {
        return 1;
    }

    /**
     * Return the tax class.
     */
    public function getTaxClass(): Models\Contracts\TaxClass
    {
        return $this->resolvedTaxClass ?? TaxClass::getDefault();
    }

    public function getTaxReference(): ?string
    {
        return $this->shippingMethod->code;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return 'shipping';
    }

    /**
     * {@inheritDoc}
     */
    public function isShippable(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {
        return $this->shippingMethod->name ?: $this->driver()->name();
    }

    /**
     * {@inheritDoc}
     */
    public function getOption(): ?string
    {
        return $this->shippingMethod->code;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions(): Collection
    {
        return collect();
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): ?string
    {
        return $this->shippingMethod->code;
    }

    public function getThumbnail(): ?string
    {
        return null;
    }

    /**
     * Return the shipping method driver.
     */
    public function getShippingOption(Cart $cart): ?ShippingOption
    {
        if (config('store.shipping-tables.shipping_rate_tax_calculation') == 'highest') {
            $this->resolvedTaxClass = $this->resolveHighestTaxRateInCart($cart);
        }

        return $this->shippingMethod->driver()->resolve(
            new ShippingOptionRequest(
                shippingRate: $this,
                cart: $cart,
            )
        );
    }

    public function canBeFulfilledAtQuantity(int $quantity): bool
    {
        return true;
    }

    public function getTotalInventory(): int
    {
        return 1;
    }

    private function resolveHighestTaxRateInCart(Cart $cart): ?TaxClass
    {
        $highestRate = false;
        $highestTaxClass = null;

        foreach ($cart->lines as $cartLine) {
            if ($cartLine->purchasable->taxClass) {
                foreach ($cartLine->purchasable->taxClass->taxRateAmounts as $amount) {
                    if ($highestRate === false || $amount->percentage > $highestRate) {
                        $highestRate = $amount->percentage;
                        $highestTaxClass = $cartLine->purchasable->taxClass;
                    }
                }
            }
        }

        return $highestTaxClass;
    }
}
