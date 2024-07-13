<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_reset_password()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->postJson('/api/password/email', ['email' => 'test@example.com']);

        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/reset-password', [
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password has been reset successfully.']);

        $this->assertTrue(\Hash::check('newpassword', $user->fresh()->password));
    }
}