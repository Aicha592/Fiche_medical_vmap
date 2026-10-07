<?php

namespace Tests\Feature\Auth;

use App\Models\Otp;
use App\Models\User;
use App\Services\OrangeSmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function pendingUser(): User
    {
        $user = User::create([
            'name' => 'OTP Test', 'email' => 'otp@example.com',
            'password' => bcrypt('password'), 'telephone' => '770000000', 'role' => 'admin',
        ]);
        $this->withSession(['user_id' => $user->id, 'phone' => $user->telephone]);
        return $user;
    }

    public function test_login_persists_the_sent_code_without_storing_it_in_session(): void
    {
        $user = $this->pendingUser();
        $this->mock(OrangeSmsService::class)->shouldReceive('sendSms')->once()
            ->withArgs(function ($phone, $message, $subject) use ($user) {
                $otp = Otp::where('user_id', $user->id)->sole();
                return $phone === $user->telephone && str_contains($message, $otp->code) && !$otp->isExpired();
            });
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('otp.form'))->assertSessionMissing('otp');
        $this->assertGuest();
    }

    public function test_valid_code_is_consumed_and_cannot_be_replayed(): void
    {
        $user = $this->pendingUser();
        Otp::create(['user_id' => $user->id, 'code' => '123456', 'expires_at' => now()->addMinutes(5)]);
        $this->post('/otp', ['otp' => '123456'])->assertRedirect(route('backoffice.dashboard'))
            ->assertSessionMissing('user_id');
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('otps', 0);
        $this->post('/logout');
        $this->withSession(['user_id' => $user->id])->post('/otp', ['otp' => '123456'])
            ->assertSessionHasErrors('otp');
        $this->assertGuest();
    }

    public function test_incorrect_expired_and_other_users_codes_are_rejected(): void
    {
        $user = $this->pendingUser();
        $otp = Otp::create(['user_id' => $user->id, 'code' => '123456', 'expires_at' => now()->addMinutes(5)]);
        $this->post('/otp', ['otp' => '654321'])->assertSessionHasErrors('otp');
        $this->assertGuest();
        $this->withSession(['user_id' => $user->id + 1])->post('/otp', ['otp' => '123456'])
            ->assertSessionHasErrors('otp');
        $this->assertGuest();
        $otp->update(['expires_at' => now()]);
        $this->withSession(['user_id' => $user->id])->post('/otp', ['otp' => '123456'])->assertSessionHasErrors('otp');
        $this->assertGuest();
    }

    public function test_resend_replaces_the_code_and_enforces_cooldown(): void
    {
        $user = $this->pendingUser();
        Otp::create(['user_id' => $user->id, 'code' => '000000', 'expires_at' => now()->subMinute()]);
        $this->mock(OrangeSmsService::class)->shouldReceive('sendSms')->once();
        $this->post('/otp/resend')->assertSessionHas('success')->assertSessionMissing('otp');
        $this->assertDatabaseCount('otps', 1);
        $otp = Otp::sole();
        $this->assertNotSame('000000', $otp->code);
        $this->assertFalse($otp->isExpired());
        $this->post('/otp', ['otp' => '000000'])->assertSessionHasErrors('otp');
        $this->assertGuest();
        $this->post('/otp/resend')->assertSessionHas('error');
        $this->assertSame($otp->code, $otp->fresh()->code);
    }
}
