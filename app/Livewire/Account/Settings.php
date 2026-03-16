<?php

namespace App\Livewire\Account;

use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Http\Requests\Account\UpdateProfileRequest;
use Illuminate\Contracts\View\View;
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
        $this->phone = $user->phone ?? '';
    }

    public function updateProfile(): void
    {
        $request = (new UpdateProfileRequest)->forUser(Auth::id());
        $validated = $request->validatePayload([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $user = Auth::user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->save();

        session()->flash('status', 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $request = new UpdatePasswordRequest;
        $validated = $request->validatePayload([
            'current_password' => $this->current_password,
            'new_password' => $this->new_password,
            'new_password_confirmation' => $this->new_password_confirmation,
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('status', 'Password updated successfully.');
    }

    public function render(): View
    {
        return view('livewire.account.settings');
    }
}
