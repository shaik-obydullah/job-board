<?php

namespace App\Livewire\Auth;

use App\Models\ActivityLog;
use App\Support\ThrottlesLivewireActions;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Sign in')]
class Login extends Component
{
    use ThrottlesLivewireActions;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function login(): void
    {
        if ($this->hitRateLimit('login', 5, 60)) {
            $this->addError('email', __('auth.throttle'));

            return;
        }

        $this->validate();

        $attempt = Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember);

        if (! $attempt) {
            $this->addError('email', 'These credentials do not match our records.');

            return;
        }

        $user = Auth::user();

        if ($user->is_banned) {
            Auth::logout();
            session()->invalidate();
            $this->addError('email', 'Your account has been suspended. Please contact support.');

            return;
        }

        $this->clearRateLimit('login');
        session()->regenerate();

        ActivityLog::record($user, 'user_login', "{$user->name} signed in.");

        $this->redirectIntended(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
