<?php

namespace Tests\Feature;

use App\Livewire\Notifications\NotificationCenter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeSeekerWithNotifications(int $count = 2): User
    {
        $seeker = User::factory()->create(['role' => 'job_seeker']);

        for ($i = 0; $i < $count; $i++) {
            $seeker->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\ApplicationSubmitted',
                'data' => ['message' => "Notification {$i}", 'url' => '/'],
            ]);
        }

        return $seeker;
    }

    public function test_user_can_see_notification_center()
    {
        $seeker = $this->makeSeekerWithNotifications();

        $response = $this->actingAs($seeker)->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('2 unread');
    }

    public function test_mark_as_read_works()
    {
        $seeker = $this->makeSeekerWithNotifications();
        $notification = $seeker->notifications()->first();

        Livewire::actingAs($seeker)
            ->test(NotificationCenter::class)
            ->call('markAsRead', $notification->id);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_mark_all_as_read_works()
    {
        $seeker = $this->makeSeekerWithNotifications(3);

        Livewire::actingAs($seeker)
            ->test(NotificationCenter::class)
            ->call('markAllAsRead');

        $this->assertEquals(0, $seeker->unreadNotifications()->count());
    }

    public function test_notification_bell_shows_count()
    {
        $seeker = $this->makeSeekerWithNotifications(2);

        $response = $this->actingAs($seeker)->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('2 unread');
    }

    public function test_clear_all_deletes_notifications()
    {
        $seeker = $this->makeSeekerWithNotifications(2);

        Livewire::actingAs($seeker)
            ->test(NotificationCenter::class)
            ->call('clearAll');

        $this->assertEquals(0, $seeker->notifications()->count());
    }
}
