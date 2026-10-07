<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class ExpiredFormTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Activer la protection CSRF, normalement ignorée pendant les tests.
        $this->app->bind(ValidateCsrfToken::class, function ($app) {
            return new class($app, $app['encrypter']) extends ValidateCsrfToken
            {
                protected function runningUnitTests()
                {
                    return false;
                }
            };
        });
    }

    public function test_expired_login_returns_to_a_fresh_form_without_replaying_credentials(): void
    {
        $this->withSession(['_token' => 'current-token'])
            ->post('/login', ['_token' => 'old-token', 'email' => 'test@example.com', 'password' => 'secret'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('session')
            ->assertSessionMissing('_old_input.password');
        $this->assertGuest();
        $this->get('/login')->assertOk()->assertSee('Le formulaire a expiré.');
    }

    public function test_expired_otp_form_preserves_pending_login(): void
    {
        foreach (['/otp', '/otp/resend'] as $uri) {
            $this->withSession(['_token' => 'current-token', 'user_id' => 123])
                ->post($uri, ['_token' => 'old-token', 'otp' => '123456'])
                ->assertRedirect(route('otp.form'))
                ->assertSessionHasErrors('session')
                ->assertSessionHas('user_id', 123);
            $this->assertGuest();
        }
    }

    public function test_expired_session_returns_to_login(): void
    {
        $this->post('/otp', ['otp' => '123456'])
            ->assertRedirect(route('login'))->assertSessionHasErrors('session');
        $this->assertGuest();
    }

    public function test_json_requests_keep_the_419_status(): void
    {
        $this->postJson('/login', ['email' => 'test@example.com', 'password' => 'secret'])
            ->assertStatus(419);
        $this->assertGuest();
    }
}
