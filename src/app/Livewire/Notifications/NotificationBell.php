<?php

namespace App\Livewire\Notifications;

use Livewire\Component;

class NotificationBell extends Component
{
    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(6)
            ->get();

        $unread = auth()->user()->unreadNotifications()->count();

        return view('livewire.notifications.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $unread,
        ]);
    }
}
