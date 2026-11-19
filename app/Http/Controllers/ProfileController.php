<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $profile = $user->profile()->firstOrCreate(
            ['user_id' => $user->id_user],
            [
                'first_name' => mb_substr($user->user_name, 0, 50),
                'last_name' => '',
                'profile_photo' => 'assets/images/perfiles/avatar.svg',
            ]
        );

        return view('social.profile-edit', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:300'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $profile = $user->profile()->firstOrCreate(
            ['user_id' => $user->id_user],
            ['first_name' => mb_substr($user->user_name, 0, 50), 'last_name' => '']
        );

        $profile->fill([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'phone' => trim($data['phone'] ?? '') ?: '000',
            'gender' => $data['gender'] ?? null,
            'city' => trim($data['city'] ?? ''),
            'bio' => trim($data['bio'] ?? ''),
        ]);

        if ($request->hasFile('photo')) {
            $profile->profile_photo = $request->file('photo')->store('profiles', 'public');
        }

        $profile->save();

        return redirect()->route('profile.edit')->with('status', 'Tu perfil se guardó correctamente.');
    }
}
