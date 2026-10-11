<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Verify your email')]
class VerifyEmail extends Component
{
    public function resend(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirect(route('dashboard'));

            return;
        }

        $user->sendEmailVerificationNotification();
        session()->flash('status', 'Verification link sent!');
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
