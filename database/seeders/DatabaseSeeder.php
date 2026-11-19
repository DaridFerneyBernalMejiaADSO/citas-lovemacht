<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            [
                'username' => 'valentina.demo',
                'email' => 'valentina.demo@lovematch.test',
                'first_name' => 'Valentina',
                'last_name' => 'Gómez',
                'birth_date' => '2002-04-12',
                'gender' => 'Mujer',
                'city' => 'Medellín',
                'bio' => 'Me gustan los viajes, la música y descubrir cafés nuevos.',
                'photo' => '/assets/images/perfiles/valentina.jpg',
            ],
            [
                'username' => 'sofia.demo',
                'email' => 'sofia.demo@lovematch.test',
                'first_name' => 'Sofía',
                'last_name' => 'Rojas',
                'birth_date' => '1997-08-03',
                'gender' => 'Mujer',
                'city' => 'Cali',
                'bio' => 'Plan tranquilo, buena conversación y algo rico para comer.',
                'photo' => '/assets/images/perfiles/sofia.jpg',
            ],
            [
                'username' => 'mariana.demo',
                'email' => 'mariana.demo@lovematch.test',
                'first_name' => 'Mariana',
                'last_name' => 'Torres',
                'birth_date' => '1999-02-19',
                'gender' => 'Mujer',
                'city' => 'Bogotá',
                'bio' => 'Disfruto la fotografía, los animales y conocer lugares nuevos.',
                'photo' => '/assets/images/perfiles/mariana.jpg',
            ],
            [
                'username' => 'mateo.demo',
                'email' => 'mateo.demo@lovematch.test',
                'first_name' => 'Mateo',
                'last_name' => 'López',
                'birth_date' => '1995-11-09',
                'gender' => 'Hombre',
                'city' => 'Pereira',
                'bio' => 'Me gusta entrenar, viajar y probar restaurantes.',
                'photo' => '/assets/images/perfiles/mateo.jpg',
            ],
        ];

        foreach ($profiles as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'user_name' => $data['username'],
                    'password' => Hash::make(Str::random(48)),
                    'role' => 'user',
                ]
            );

            $user->forceFill(['role' => 'user'])->save();
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id_user],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => '0000000000',
                    'birth_date' => $data['birth_date'],
                    'gender' => $data['gender'],
                    'city' => $data['city'],
                    'bio' => $data['bio'],
                    'profile_photo' => $data['photo'],
                    'is_demo' => true,
                ]
            );
        }
    }
}
