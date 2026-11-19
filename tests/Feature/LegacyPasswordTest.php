<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LegacyPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_legacy_password_is_upgraded_after_login(): void
    {
        $plainPassword = 'Old-password-123';
        $key = (string) config('auth.legacy_password_key');
        $legacyHash = substr(hash_hmac('sha512', $plainPassword, $key), 0, 50);

        User::create([
            'user_name' => 'legacy@example.test',
            'email' => 'legacy@example.test',
            'password' => $legacyHash,
            'role' => 'user',
        ]);

        $this->post(route('login.store'), [
            'email' => 'legacy@example.test',
            'password' => $plainPassword,
        ])->assertRedirect(route('discover.index'));

        $this->assertAuthenticated();
        $this->assertNotSame($legacyHash, User::where('email', 'legacy@example.test')->value('password'));
        $this->assertTrue(Hash::check(
            $plainPassword,
            User::where('email', 'legacy@example.test')->value('password')
        ));
    }
}
