@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')
<p class="eyebrow">QUÉ BUENO VERTE</p>
<h1 class="login__title">Inicia sesión</h1>
<p class="auth-description">Entra para descubrir personas y continuar tus conversaciones.</p>

<form class="login" action="{{ route('login.store') }}" method="post">
    @csrf
    @if (session('status'))
        <span class="login__success" role="status">{{ session('status') }}</span>
    @endif
    <label class="auth-field" for="email">Correo electrónico
        <input class="login__input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
        @error('email') <span class="login__error">{{ $message }}</span> @enderror
    </label>
    <label class="auth-field" for="password">Contraseña
        <input class="login__input" id="password" name="password" type="password" autocomplete="current-password" required>
        @error('password') <span class="login__error">{{ $message }}</span> @enderror
    </label>
    <label class="remember">
        <input class="remember_checkbox" type="checkbox" name="remember" value="1" @checked(old('remember'))>
        <span class="remember_label">Mantener mi sesión</span>
    </label>
    <button class="login__btn" type="submit">Entrar</button>
    <span class="login__separator" aria-hidden="true"></span>
    <p class="auth-switch">¿Aún no tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
</form>
@endsection
