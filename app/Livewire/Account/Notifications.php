<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Notifications')]
class Notifications extends Component
{
    public array $preferences = [
        'order_updates' => true,
        'promotions' => false,
        'stock_alerts' => true,
        'newsletter' => false,
    ];

    public function mount(): void
    {
        $user = Auth::user();
        $customer = $user->latestCustomer();
        if ($customer && isset($customer->meta['notification_preferences'])) {
            $this->preferences = array_merge($this->preferences, $customer->meta['notification_preferences']);
        }
    }

    public function updatePreferences(): void
    {
        $customer = Auth::user()->latestCustomer();
        $meta = $customer->meta ?? [];
        $meta['notification_preferences'] = $this->preferences;
        $customer->update(['meta' => $meta]);

        session()->flash('status', 'Notification preferences updated successfully.');
    }

    public function render()
    {
        return view('livewire.account.notifications');
    }
}
