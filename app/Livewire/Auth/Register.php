<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Register')]
class Register extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|email|max:255|unique:users')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $this->validate();

        DB::transaction(function () {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Create a customer record for this user
            $names = explode(' ', $this->name, 2);
            $firstName = $names[0];
            $lastName = $names[1] ?? '';

            $customer = Customer::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]);

            $user->customers()->attach($customer);

            Auth::login($user);
        });

        $this->redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
