<?php

namespace App\Livewire\Notifications;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Notifications')]
class NotificationCenter extends Component
{
    use WithPagination;

    public function markAsRead(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function clearAll(): void
    {
        auth()->user()->notifications()->delete();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);

        return view('livewire.notifications.notification-center', [
            'notifications' => $notifications,
            'unreadCount' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
