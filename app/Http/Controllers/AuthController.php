<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'El correo o la contraseña no son correctos.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = Auth::user();

        return redirect()->intended(
            $user instanceof User && $user->role === 'admin'
                ? route('admin.support.index')
                : route('discover.index')
        );
    }

    public function showRegistration(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $email = $request->input('email');

        if (is_string($email)) {
            $request->merge(['email' => mb_strtolower(trim($email))]);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birth_date' => ['required', 'date', 'before_or_equal:'.today()->subYears(18)->toDateString()],
            'gender' => ['required', Rule::in(['Mujer', 'Hombre', 'No binario', 'Prefiero no decirlo'])],
            'city' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $data['email'];

        $user = DB::transaction(function () use ($data, $email): User {
            $user = User::create([
                'user_name' => $email,
                'email' => $email,
                'password' => Hash::make($data['password']),
                'role' => 'user',
            ]);

            $user->profile()->create([
                'first_name' => trim($data['first_name']),
                'last_name' => trim($data['last_name']),
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'city' => trim($data['city']),
                'profile_photo' => 'assets/images/perfiles/avatar.svg',
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('discover.index')->with('status', 'Tu cuenta está lista. ¡Empieza a descubrir!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
