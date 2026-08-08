<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthFlowTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_guest_can_view_homepage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Find your next')
                ->assertSee('career move');
        });
    }

    public function test_user_can_log_in_and_see_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'dusk@example.com',
            'password' => 'password',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('#email', $user->email)
                ->type('#password', 'password')
                ->press('Sign in')
                ->waitForLocation('/dashboard')
                ->assertSee('Welcome back, '.$user->name);
        });
    }
}
