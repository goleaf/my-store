<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Sign In')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $request = new LoginRequest;
        $validated = $request->validatePayload([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (Auth::attempt($validated, $this->remember)) {
            session()->regenerate();

            $this->redirectIntended(route('home'));

            return;
        }

        $this->addError('email', trans('auth.failed'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
