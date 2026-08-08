<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manage Users')]
class AdminUsers extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function toggleBan(User $user): void
    {
        $user->update(['is_banned' => ! $user->is_banned]);
        session()->flash('success', $user->is_banned ? "{$user->name} has been suspended." : "{$user->name} has been reinstated.");
    }

    public function updateRole(User $user, string $role): void
    {
        if (! in_array($role, [User::ROLE_JOB_SEEKER, User::ROLE_EMPLOYER, User::ROLE_ADMIN], true)) {
            return;
        }

        if ($user->id === auth()->id() && $role !== User::ROLE_ADMIN) {
            session()->flash('error', 'You cannot demote your own account.');

            return;
        }

        $user->update(['role' => $role]);
        session()->flash('success', "{$user->name}'s role updated to {$role}.");
    }

    public function render()
    {
        $query = User::query()->withCount('applications');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('username', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        $users = $query->latest()->paginate(15);

        return view('livewire.admin.admin-users', ['users' => $users]);
    }
}
