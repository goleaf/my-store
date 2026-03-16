<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    public string $email = '';

    public ?string $status = null;

    public function sendResetLink(): void
    {
        $request = new ForgotPasswordRequest;
        $validated = $request->validatePayload([
            'email' => $this->email,
        ]);

        $status = Password::sendResetLink(
            $validated
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
