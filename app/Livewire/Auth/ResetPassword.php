<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    public string $token;

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    public function resetPassword(): void
    {
        $request = new ResetPasswordRequest;
        $validated = $request->validatePayload([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => $validated['email'],
                'password' => $validated['password'],
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', trans($status));

            $this->redirect(route('login'));

            return;
        }

        $this->addError('email', trans($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
