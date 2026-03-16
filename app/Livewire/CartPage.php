<?php
namespace App\Livewire;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use App\Store\Facades\CartSession;
class CartPage extends Component
{
    /**
     * The editable cart lines.
     */
    public array $lines = [];

    /**
     * The coupon code to apply.
     */
    public ?string $couponCode = null;

    public function rules(): array
    {
        return [
            'lines.*.quantity' => 'required|numeric|min:1|max:10000',
            'couponCode' => 'nullable|string|max:255',
        ];
    }

    public function mount(): void
    {
        $this->couponCode = $this->cart->coupon_code;
        $this->mapLines();
    }
    /**
     * Get the current cart instance.
     */
    public function getCartProperty()
    {
        return CartSession::current();
    }
    /**
     * Return the cart lines from the cart.
     */
    public function getCartLinesProperty(): Collection
    {
        return $this->cart->lines ?? collect();
    }
    /**
     * Update the cart lines.
     */
    public function updateLines(): void
    {
        $this->validate();
        CartSession::updateLines(
            collect($this->lines)
        );
        $this->mapLines();
        $this->dispatch('cartUpdated');
    }
    public function removeLine($id): void
    {
        CartSession::remove($id);
        $this->mapLines();
        $this->dispatch('cartUpdated');
    }

    /**
     * Apply a coupon to the cart.
     */
    public function applyCoupon(): void
    {
        $this->validateOnly('couponCode');

        if (! $this->couponCode) {
            return;
        }

        CartSession::setCoupon($this->couponCode);
        $this->mapLines();
        session()->flash('coupon_status', 'Coupon applied!');
    }

    /**
     * Remove a coupon from the cart.
     */
    public function removeCoupon(): void
    {
        CartSession::setCoupon(null);
        $this->couponCode = null;
        $this->mapLines();
        session()->flash('coupon_status', 'Coupon removed.');
    }

    /**
     * Map the cart lines.
     */
    public function mapLines(): void
    {
        $this->lines = $this->cartLines->map(function ($line) {
            return [
                'id' => $line->id,
                'identifier' => $line->purchasable->getIdentifier(),
                'quantity' => $line->quantity,
                'description' => $line->purchasable->getDescription(),
                'thumbnail' => $line->purchasable->getThumbnail()?->getUrl(),
                'option' => $line->purchasable->getOption(),
                'options' => $line->purchasable->getOptions()->implode(' / '),
                'sub_total' => $line->subTotal->formatted(),
                'unit_price' => $line->unitPrice->formatted(),
            ];
        })->toArray();
    }
    public function render(): View
    {
        return view('livewire.cart-page')
            ->layout('layouts.storefront');
    }
}
