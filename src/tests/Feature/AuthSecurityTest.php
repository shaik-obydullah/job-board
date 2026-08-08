<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'security@example.com',
            'password' => 'password',
        ]);
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        $component = Livewire::test(Login::class);

        for ($i = 0; $i < 5; $i++) {
            $component->set('email', 'security@example.com')
                ->set('password', 'wrong-password')
                ->call('login');
        }

        $component->set('email', 'security@example.com')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors('email');
    }

    public function test_successful_login_resets_the_rate_limit(): void
    {
        $component = Livewire::test(Login::class);

        for ($i = 0; $i < 4; $i++) {
            $component->set('email', 'security@example.com')
                ->set('password', 'wrong-password')
                ->call('login');
        }

        $component->set('email', 'security@example.com')
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($this->user);
    }

    public function test_register_requires_recaptcha_token_when_configured(): void
    {
        config(['services.recaptcha.site_key' => 'site-key']);
        config(['services.recaptcha.secret_key' => 'secret-key']);

        Livewire::test(Register::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors('recaptcha_token');

        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }

    public function test_register_accepts_valid_recaptcha_token(): void
    {
        config(['services.recaptcha.site_key' => 'site-key']);
        config(['services.recaptcha.secret_key' => 'secret-key']);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);

        Livewire::test(Register::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('recaptcha_token', 'valid-token')
            ->call('register')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }

    public function test_register_is_throttled(): void
    {
        $component = Livewire::test(Register::class);

        for ($i = 0; $i < 5; $i++) {
            $component->set('name', 'Jane Doe')
                ->set('email', 'jane'.$i.'@example.com')
                ->set('password', 'password123')
                ->set('password_confirmation', 'a-different-password')
                ->call('register');
        }

        $component->set('name', 'Jane Doe')
            ->set('email', 'blocked@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'a-different-password')
            ->call('register')
            ->assertHasErrors('name');

        $this->assertDatabaseMissing('users', ['email' => 'blocked@example.com']);
    }
}
