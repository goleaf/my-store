<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public ?string $status = null;

    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = trans($status);
            $this->email = '';

            return;
        }

        $this->addError('email', trans($status));
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
