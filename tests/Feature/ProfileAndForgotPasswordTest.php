<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Mail\TemporaryPasswordMail;

class ProfileAndForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'kasub']);
        Role::firstOrCreate(['name' => 'kabid']);
    }

    public function test_forgot_password_blocked_without_2_failed_attempts()
    {
        // Without any session state, POST to forgot-password should be rejected
        $response = $this->post(route('password.email'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('username');
    }

    public function test_forgot_password_button_hidden_after_1_failed_attempt()
    {
        $user = User::factory()->create([
            'email' => 'kabid@example.com',
            'password' => Hash::make('correctpassword'),
        ]);
        $user->assignRole('kabid');

        // First failed attempt
        $response = $this->post(route('login'), [
            'username' => 'kabid@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHas('login_failed_attempts', 1);
    }

    public function test_forgot_password_button_visible_after_2_failed_attempts()
    {
        $user = User::factory()->create([
            'email' => 'kabid@example.com',
            'password' => Hash::make('correctpassword'),
        ]);
        $user->assignRole('kabid');

        // First failed attempt
        $this->post(route('login'), [
            'username' => 'kabid@example.com',
            'password' => 'wrongpassword',
        ]);

        // Second failed attempt
        $response = $this->post(route('login'), [
            'username' => 'kabid@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHas('login_failed_attempts', 2);
        $response->assertSessionHas('login_attempted_email', 'kabid@example.com');
    }

    public function test_admin_cannot_use_forgot_password()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        // Simulate 2 failed login attempts via session
        $response = $this->withSession([
            'login_failed_attempts' => 2,
            'login_attempted_email' => 'admin@example.com',
        ])->post(route('password.email'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('username');
    }

    public function test_kasub_can_use_forgot_password_after_2_failed_attempts()
    {
        Mail::fake();

        $kasub = User::factory()->create([
            'email' => 'kasub@example.com',
            'recovery_email' => 'kasub@example.com',
        ]);
        $kasub->assignRole('kasub');

        $oldPasswordHash = $kasub->password;

        // Simulate 2 failed attempts + email in session, then trigger forgot password
        $response = $this->withSession([
            'login_failed_attempts' => 2,
            'login_attempted_email' => 'kasub@example.com',
        ])->post(route('password.email'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('forgot_status');

        $kasub->refresh();
        $this->assertNotEquals($oldPasswordHash, $kasub->password);

        // Email sent to user's own email (login email)
        Mail::assertSent(TemporaryPasswordMail::class, function ($mail) use ($kasub) {
            return $mail->hasTo('kasub@example.com')
                && $mail->accounts[0]['email'] === 'kasub@example.com';
        });
    }

    public function test_failed_attempts_reset_on_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'kabid@example.com',
            'password' => Hash::make('correctpassword'),
        ]);
        $user->assignRole('kabid');

        // Simulate 2 failed attempts, then login correctly
        $response = $this->withSession([
            'login_failed_attempts' => 2,
            'login_attempted_email' => 'kabid@example.com',
        ])->post(route('login'), [
            'username' => 'kabid@example.com',
            'password' => 'correctpassword',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionMissing('login_failed_attempts');
        $response->assertSessionMissing('login_attempted_email');
    }

    public function test_failed_attempts_reset_when_email_changes()
    {
        $user = User::factory()->create([
            'email' => 'kabid@example.com',
            'password' => Hash::make('correctpassword'),
        ]);
        $user->assignRole('kabid');

        // First attempt with one email
        $this->post(route('login'), [
            'username' => 'kabid@example.com',
            'password' => 'wrongpassword',
        ]);

        // Second attempt with DIFFERENT email — counter should reset
        $response = $this->post(route('login'), [
            'username' => 'other@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHas('login_failed_attempts', 1);
        $response->assertSessionHas('login_attempted_email', 'other@example.com');
    }

    public function test_admin_cannot_access_profile()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('profile'));
        $response->assertRedirect(route('dashboard'));
    }

    public function test_kasub_can_change_password_in_profile()
    {
        $kasub = User::factory()->create([
            'password' => Hash::make('oldpassword')
        ]);
        $kasub->assignRole('kasub');

        $response = $this->actingAs($kasub)->get(route('profile'));
        $response->assertStatus(200);

        $response = $this->actingAs($kasub)->post(route('profile.update-password'), [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
        $kasub->refresh();
        $this->assertTrue(Hash::check('newpassword123', $kasub->password));
    }
}
