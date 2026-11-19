<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_people_under_eighteen_cannot_register(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), $this->registrationData([
                'email' => 'minor@example.test',
                'birth_date' => today()->subYears(17)->toDateString(),
            ]))
            ->assertSessionHasErrors('birth_date');

        $this->assertDatabaseMissing('users', ['email' => 'minor@example.test']);
    }

    public function test_registration_always_creates_a_client_account(): void
    {
        $this->post(route('register.store'), $this->registrationData([
            'email' => 'client@example.test',
            'birth_date' => '1990-01-01',
            'role' => 'admin',
        ]))->assertRedirect(route('discover.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'client@example.test',
            'role' => 'user',
        ]);
        $this->assertDatabaseHas('profiles', [
            'first_name' => 'Cliente',
            'user_id' => User::where('email', 'client@example.test')->value('id_user'),
        ]);
    }

    private function registrationData(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Cliente',
            'last_name' => 'Prueba',
            'email' => 'new@example.test',
            'birth_date' => '1990-01-01',
            'gender' => 'Prefiero no decirlo',
            'city' => 'Bogotá',
            'password' => 'Password-123',
            'password_confirmation' => 'Password-123',
        ], $overrides);
    }
}
