<?php

namespace Tests\Feature\Backoffice;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpListTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'name' => 'Utilisateur OTP', 'email' => $role.'@example.com',
            'telephone' => '770000000', 'password' => bcrypt('password'), 'role' => $role,
        ]);
    }

    public function test_admin_sees_active_codes_and_user_details(): void
    {
        $this->freezeTime();
        $admin = $this->user('admin');
        $expiration = now()->addMinutes(5);
        Otp::create(['user_id' => $admin->id, 'code' => '123456', 'expires_at' => $expiration]);
        Otp::create(['user_id' => $admin->id, 'code' => '987654', 'expires_at' => now()]);
        $response = $this->actingAs($admin)->get(route('backoffice.users.otps'));
        $response->assertOk()->assertSee($admin->name)->assertSee($admin->email)
            ->assertSee('123456')->assertDontSee('987654')
            ->assertSee($expiration->timezone('Africa/Dakar')->format('d/m/Y H:i:s'));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->get(route('backoffice.users.index'))->assertSee(route('backoffice.users.otps'));
    }

    public function test_non_admin_cannot_access_codes(): void
    {
        $this->actingAs($this->user('rh'))->get(route('backoffice.users.otps'))->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('backoffice.users.otps'))->assertRedirect(route('login'));
    }

    public function test_empty_list_is_displayed(): void
    {
        $this->actingAs($this->user('admin'))->get(route('backoffice.users.otps'))
            ->assertOk()->assertSee('Aucun code OTP actif.');
    }
}
