<?php

namespace App\Livewire\Account;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Order Details')]
class OrderDetails extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        $this->order = $order->load(['lines.purchasable', 'addresses']);
    }

    public function render()
    {
        return view('livewire.account.order-details');
    }
}
