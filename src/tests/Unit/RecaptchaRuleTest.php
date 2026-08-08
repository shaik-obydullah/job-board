<?php

namespace Tests\Unit;

use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaRuleTest extends TestCase
{
    public function test_rule_passes_when_recaptcha_is_not_configured(): void
    {
        config(['services.recaptcha.secret_key' => null]);

        $failed = false;
        (new Recaptcha)->validate('recaptcha_token', 'anything', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed);
    }

    public function test_rule_passes_for_valid_token(): void
    {
        config(['services.recaptcha.secret_key' => 'secret-key']);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);

        $failed = false;
        (new Recaptcha)->validate('recaptcha_token', 'valid-token', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed);
    }

    public function test_rule_fails_for_invalid_token(): void
    {
        config(['services.recaptcha.secret_key' => 'secret-key']);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false]),
        ]);

        $failed = false;
        (new Recaptcha)->validate('recaptcha_token', 'invalid-token', function () use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed);
    }
}
