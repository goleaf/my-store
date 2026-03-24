<?php

namespace App\Livewire\Components;

use App\Facades\CartSession;
use App\Http\Requests\Cart\UpdateCartLinesRequest;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Cart extends Component
{
    /**
     * The editable cart lines.
     */
    public array $lines;

    public bool $linesVisible = false;

    protected $listeners = [
        'add-to-cart' => 'handleAddToCart',
    ];

    public function mount(): void
    {
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
     * Get the total quantity of items in the cart.
     */
    public function getTotalQuantityProperty(): int
    {
        return $this->cartLines->sum('quantity');
    }

    /**
     * Update the cart lines.
     */
    public function updateLines(): void
    {
        $request = new UpdateCartLinesRequest;
        $request->validatePayload([
            'lines' => $this->lines,
        ]);

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
    }

    /**
     * Map the cart lines.
     *
     * We want to map out our cart lines like this so we can
     * add some validation rules and make them editable.
     */
    public function mapLines(): void
    {
        $this->lines = $this->cartLines->map(function ($line) {
            $purchasable = $line->purchasable;

            return [
                'id' => $line->id,
                'identifier' => $purchasable?->getIdentifier() ?? 'Unavailable product',
                'quantity' => $line->quantity,
                'description' => $purchasable?->getDescription() ?? 'This product is no longer available.',
                'thumbnail' => $purchasable?->getThumbnail()?->getUrl(),
                'option' => $purchasable?->getOption(),
                'options' => $purchasable ? $purchasable->getOptions()->implode(' / ') : null,
                'sub_total' => $line->subTotal->formatted(),
                'unit_price' => $line->unitPrice->formatted(),
            ];
        })->toArray();
    }

    public function handleAddToCart(): void
    {
        $this->mapLines();
        $this->linesVisible = true;
    }

    public function render(): View
    {
        return view('livewire.components.cart');
    }
}
