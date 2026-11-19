<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;

class LegacyUserProvider extends EloquentUserProvider
{
    public function validateCredentials(AuthenticatableContract $user, array $credentials): bool
    {
        $storedHash = (string) $user->getAuthPassword();

        if (Hash::info($storedHash)['algoName'] === 'bcrypt') {
            return parent::validateCredentials($user, $credentials);
        }

        $password = $credentials['password'] ?? null;
        $key = (string) config('auth.legacy_password_key');

        if (! is_string($password) || $key === '') {
            return false;
        }

        $legacyHash = substr(hash_hmac('sha512', $password, $key), 0, 50);

        if (! hash_equals($storedHash, $legacyHash)) {
            return false;
        }

        $user->forceFill(['password' => Hash::make($password)])->save();

        return true;
    }
}
