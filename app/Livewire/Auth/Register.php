<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Register')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $request = new RegisterRequest;
        $validated = $request->validatePayload([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'locale' => config('app.locale'),
        ]);

        Auth::login($customer);

        $this->redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
