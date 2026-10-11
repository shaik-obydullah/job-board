<?php

namespace App\Livewire\Auth;

use App\Models\ActivityLog;
use App\Models\User;
use App\Rules\Recaptcha;
use App\Support\ThrottlesLivewireActions;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Create account')]
class Register extends Component
{
    use ThrottlesLivewireActions;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'job_seeker';

    public string $recaptcha_token = '';

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:job_seeker,employer'],
        ];

        if (config('services.recaptcha.secret_key')) {
            $rules['recaptcha_token'] = ['required', new Recaptcha];
        }

        return $rules;
    }

    public function register(): void
    {
        if ($this->hitRateLimit('register', 5, 60)) {
            $this->addError('name', 'Too many registration attempts. Please try again later.');

            return;
        }

        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'username' => $this->generateUsername(),
            'email' => strtolower($this->email),
            'password' => $this->password,
            'role' => $this->role,
        ]);

        $this->clearRateLimit('register');

        event(new Registered($user));

        Auth::login($user);

        ActivityLog::record($user, 'user_registered', "{$user->name} created an account as ".$this->role.'.');

        $this->redirect(
            $this->role === 'employer' ? route('company.settings') : route('profile.edit')
        );
    }

    private function generateUsername(): string
    {
        $base = Str::slug($this->name).'-'.Str::lower(Str::random(4));

        return User::where('username', $base)->exists() ? $base.Str::random(2) : $base;
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
