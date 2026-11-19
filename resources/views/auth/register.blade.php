@extends('layouts.auth')

@section('title', 'Crear cuenta')
@section('card-class', 'auth-card--wide')

@section('content')
<p class="eyebrow">TU PRÓXIMA HISTORIA EMPIEZA AQUÍ</p>
<h1 class="login__title">Crea tu cuenta</h1>
<p class="auth-description">Regístrate gratis. Para cuidar la comunidad, debes tener 18 años o más.</p>

<form class="login register-form" action="{{ route('register.store') }}" method="post">
    @csrf
    <div class="register-grid">
        <label class="auth-field" for="first_name">Nombre
            <input class="login__input" id="first_name" name="first_name" value="{{ old('first_name') }}" maxlength="50" autocomplete="given-name" required>
            @error('first_name') <span class="login__error">{{ $message }}</span> @enderror
        </label>
        <label class="auth-field" for="last_name">Apellido
            <input class="login__input" id="last_name" name="last_name" value="{{ old('last_name') }}" maxlength="50" autocomplete="family-name" required>
            @error('last_name') <span class="login__error">{{ $message }}</span> @enderror
        </label>
        <label class="auth-field" for="email">Correo electrónico
            <input class="login__input" id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required>
            @error('email') <span class="login__error">{{ $message }}</span> @enderror
        </label>
        <label class="auth-field" for="birth_date">Fecha de nacimiento
            <input class="login__input" id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}" max="{{ now()->subYears(18)->toDateString() }}" autocomplete="bday" required>
            @error('birth_date') <span class="login__error">{{ $message }}</span> @enderror
        </label>
        <label class="auth-field" for="gender">Me identifico como
            <select class="login__input" id="gender" name="gender" required>
                <option value="">Selecciona una opción</option>
                @foreach (['Mujer', 'Hombre', 'No binario', 'Prefiero no decirlo'] as $gender)
                    <option value="{{ $gender }}" @selected(old('gender') === $gender)>{{ $gender }}</option>
                @endforeach
            </select>
            @error('gender') <span class="login__error">{{ $message }}</span> @enderror
        </label>
        <label class="auth-field" for="city">Ciudad
            <input class="login__input" id="city" name="city" value="{{ old('city') }}" maxlength="100" autocomplete="address-level2" required>
            @error('city') <span class="login__error">{{ $message }}</span> @enderror
        </label>
        <label class="auth-field" for="password">Contraseña
            <input class="login__input" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
            @error('password') <span class="login__error">{{ $message }}</span> @enderror
        </label>
        <label class="auth-field" for="password_confirmation">Confirmar contraseña
            <input class="login__input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
        </label>
    </div>
    <button class="login__btn" type="submit">Crear mi cuenta</button>
    <p class="auth-switch">¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</form>
@endsection
