<?php

namespace App\Livewire\Auth;

use App\Support\ThrottlesLivewireActions;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Forgot password')]
class ForgotPassword extends Component
{
    use ThrottlesLivewireActions;

    public string $email = '';

    public string $status = '';

    public function rules(): array
    {
        return ['email' => ['required', 'email', 'exists:users,email']];
    }

    public function sendResetLink(): void
    {
        if ($this->hitRateLimit('forgot_password', 5, 60)) {
            $this->addError('email', __('auth.throttle'));

            return;
        }

        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __($status);
            $this->email = '';
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
