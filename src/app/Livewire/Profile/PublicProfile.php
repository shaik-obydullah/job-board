<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PublicProfile extends Component
{
    public User $user;

    public function mount(string $username): void
    {
        $this->user = User::where('username', $username)->firstOrFail();

        abort_unless($this->user->visibility !== 'private', 404);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.profile.public-profile', [
            'user' => $this->user->load([
                'educations',
                'employments',
                'certifications',
                'languages',
                'portfolios',
                'memberships',
                'awards',
                'hobbies',
                'references',
                'skills',
            ]),
        ])->title($this->user->name.' | Public Profile');
    }
}
