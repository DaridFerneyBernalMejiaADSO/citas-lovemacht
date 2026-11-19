<?php

namespace Tests\Feature;

use App\Models\ConnectionMatch;
use App\Models\Profile;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_discovery_only_shows_adult_client_profiles(): void
    {
        $client = $this->createUser('client@example.test', 'user', 'Cliente visible');
        $this->createUser('admin@example.test', 'admin', 'Administrador oculto');
        $this->createUser('minor@example.test', 'user', 'Menor oculto', today()->subYears(17)->toDateString());
        $this->createUser('adult@example.test', 'user', 'Adulto visible');

        $this->actingAs($client)
            ->get(route('discover.index'))
            ->assertOk()
            ->assertSee('Adulto visible')
            ->assertDontSee('Administrador oculto')
            ->assertDontSee('Menor oculto');
    }

    public function test_only_mutual_likes_create_a_chat_and_participants_can_open_it(): void
    {
        $alice = $this->createUser('alice@example.test', 'user', 'Alice');
        $bob = $this->createUser('bob@example.test', 'user', 'Bob');
        $outsider = $this->createUser('outsider@example.test', 'user', 'Otra persona');

        $this->actingAs($alice)
            ->post(route('discover.action', $bob), ['action' => 'like'])
            ->assertRedirect(route('discover.index'));
        $this->assertDatabaseCount('matches', 0);

        $this->actingAs($bob)
            ->post(route('discover.action', $alice), ['action' => 'like'])
            ->assertRedirect(route('discover.index'));
        $this->assertDatabaseCount('matches', 1);

        $match = ConnectionMatch::firstOrFail();

        $this->actingAs($alice)->get(route('chats.show', $match))->assertOk();
        $this->actingAs($outsider)->get(route('chats.show', $match))->assertNotFound();
    }

    public function test_only_administrators_can_answer_support_requests(): void
    {
        $client = $this->createUser('support-client@example.test', 'user', 'Cliente');
        $admin = $this->createUser('support-admin@example.test', 'admin', 'Administrador');
        $supportRequest = SupportRequest::create([
            'user_id' => $client->id_user,
            'subject' => 'Necesito ayuda',
            'message' => 'No puedo actualizar mi perfil.',
            'status' => 'abierto',
        ]);

        $this->actingAs($client)->get(route('admin.support.index'))->assertForbidden();

        $this->actingAs($admin)
            ->patch(route('admin.support.update', $supportRequest), [
                'admin_response' => 'Te ayudaremos con tu perfil.',
                'status' => 'respondido',
            ])
            ->assertRedirect(route('admin.support.index'));

        $this->assertDatabaseHas('support_requests', [
            'id_request' => $supportRequest->id_request,
            'status' => 'respondido',
            'responded_by' => $admin->id_user,
        ]);
    }

    private function createUser(string $email, string $role, string $name, ?string $birthDate = '1990-01-01'): User
    {
        $user = User::create([
            'user_name' => $email,
            'email' => $email,
            'password' => 'test-password',
            'role' => $role,
        ]);

        Profile::create([
            'user_id' => $user->id_user,
            'first_name' => $name,
            'last_name' => 'Prueba',
            'birth_date' => $birthDate,
            'gender' => 'Prefiero no decirlo',
            'city' => 'Bogotá',
            'bio' => 'Perfil de prueba.',
            'profile_photo' => 'assets/images/perfiles/avatar.svg',
        ]);

        return $user;
    }
}
