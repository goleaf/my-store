<?php

namespace App\Livewire\Account;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Your Orders')]
class Orders extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = Auth::user()->orders()
            ->latest()
            ->paginate(10);

        return view('livewire.account.orders', [
            'orders' => $orders,
        ]);
    }
}
