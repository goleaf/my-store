<?php

namespace App\Livewire\Components;

use App\Base\Purchasable;
use App\Facades\CartSession;
use App\Http\Requests\Cart\AddToCartRequest;
use Illuminate\View\View;
use Livewire\Component;

class AddToCart extends Component
{
    /**
     * The purchasable model we want to add to the cart.
     */
    public ?Purchasable $purchasable = null;

    /**
     * The quantity to add to cart.
     */
    public int $quantity = 1;

    public function addToCart(): void
    {
        $request = new AddToCartRequest;
        $request->validatePayload([
            'quantity' => $this->quantity,
        ]);

        if ($this->purchasable->stock < $this->quantity) {
            $this->addError('quantity', 'The quantity exceeds the available stock.');

            return;
        }

        CartSession::manager()->add($this->purchasable, $this->quantity);
        $this->dispatch('add-to-cart');
    }

    public function render(): View
    {
        return view('livewire.components.add-to-cart');
    }
}
