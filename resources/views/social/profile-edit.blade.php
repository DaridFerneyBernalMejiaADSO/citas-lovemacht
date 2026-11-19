@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
<main class="social-shell">
    <section class="page-heading">
        <div>
            <p class="eyebrow">TU PERFIL, A TU MANERA</p>
            <h1>Mi perfil</h1>
            <p>Comparte lo que quieras para que otras personas puedan conocerte.</p>
        </div>
    </section>

    <section class="profile-edit-card">
        <div class="profile-edit-card__preview">
            <img src="{{ $profile->photo_url }}" alt="Tu foto de perfil">
            <h2>{{ $profile->first_name }} {{ $profile->last_name }}</h2>
            <p>{{ $profile->age ? $profile->age . ' años' : 'Completa tu fecha de nacimiento' }}</p>
        </div>
        <form class="stack-form" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="profile-form-grid">
                <label>Nombre
                    <input name="first_name" value="{{ old('first_name', $profile->first_name) }}" maxlength="50" required>
                    @error('first_name') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <label>Apellido
                    <input name="last_name" value="{{ old('last_name', $profile->last_name) }}" maxlength="50" required>
                    @error('last_name') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <label>Ciudad
                    <input name="city" value="{{ old('city', $profile->city) }}" maxlength="100">
                    @error('city') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <label>Teléfono (opcional)
                    <input name="phone" value="{{ old('phone', $profile->phone === '000' ? '' : $profile->phone) }}" maxlength="15" autocomplete="tel">
                    @error('phone') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <label>Me identifico como
                    <select name="gender">
                        <option value="">Prefiero no decirlo</option>
                        @foreach (['Mujer', 'Hombre', 'No binario', 'Prefiero no decirlo'] as $gender)
                            <option value="{{ $gender }}" @selected(old('gender', $profile->gender) === $gender)>{{ $gender }}</option>
                        @endforeach
                    </select>
                    @error('gender') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <label class="profile-form-grid__full">Sobre mí
                    <textarea name="bio" rows="4" maxlength="300">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <label class="profile-form-grid__full">Foto (JPG, PNG o WebP; máximo 5 MB)
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                    @error('photo') <span class="login__error">{{ $message }}</span> @enderror
                </label>
            </div>
            <button class="button" type="submit">Guardar cambios</button>
        </form>
    </section>
</main>
@endsection
