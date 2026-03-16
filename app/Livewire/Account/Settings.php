<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Account Settings')]
class Settings extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';

    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        // Phone might not exist on User model, check Customer
        $customer = $user->latestCustomer();
        if ($customer) {
            $this->phone = $customer->meta['phone'] ?? '';
        }
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $customer = $user->latestCustomer();
        if ($customer) {
            $customer->update([
                'meta' => array_merge($customer->meta?->toArray() ?? [], ['phone' => $this->phone]),
            ]);
        }

        session()->flash('status', 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('status', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.account.settings');
    }
}
