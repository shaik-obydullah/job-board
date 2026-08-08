<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        Livewire::test(Register::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('role', 'job_seeker')
            ->call('register')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'role' => 'job_seeker',
        ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_register_as_employer()
    {
        Livewire::test(Register::class)
            ->set('name', 'Acme HR')
            ->set('email', 'hr@acme.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('role', 'employer')
            ->call('register')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'email' => 'hr@acme.com',
            'role' => 'employer',
        ]);
    }

    public function test_user_cannot_register_with_mismatched_passwords()
    {
        Livewire::test(Register::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'different')
            ->set('role', 'job_seeker')
            ->call('register')
            ->assertHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create(['email' => 'login@example.com']);

        Livewire::test(Login::class)
            ->set('email', 'login@example.com')
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_fail_with_wrong_password()
    {
        User::factory()->create(['email' => 'login@example.com']);

        Livewire::test(Login::class)
            ->set('email', 'login@example.com')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_cannot_login_when_banned()
    {
        User::factory()->create([
            'email' => 'banned@example.com',
            'is_banned' => true,
        ]);

        Livewire::test(Login::class)
            ->set('email', 'banned@example.com')
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
