<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use App\Facades\CartSession;
use App\Models\Cart;
use App\Models\Order;

class CheckoutSuccessPage extends Component
{
    public ?Cart $cart;

    public Order $order;

    public function mount(): void
    {
        $this->cart = CartSession::current();
        if (! $this->cart || ! $this->cart->completedOrder) {
            $this->redirect('/');

            return;
        }
        $this->order = $this->cart->completedOrder;

        CartSession::forget();
    }

    public function render(): View
    {
        return view('livewire.checkout-success-page');
    }
}
