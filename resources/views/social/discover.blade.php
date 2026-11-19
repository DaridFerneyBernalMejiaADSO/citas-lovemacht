@extends('layouts.app')

@section('title', 'Descubre personas')

@section('content')
<main class="social-shell discover-page">
    <section class="page-heading">
        <div>
            <p class="eyebrow">PERSONAS PARA CONOCER</p>
            <h1>Descubre</h1>
            <p>Un perfil a la vez. Si el interés es mutuo, podrán conversar.</p>
        </div>
        <span class="age-safety"><span aria-hidden="true">♡</span> Solo mayores de 18</span>
    </section>

    @if ($profiles->isEmpty())
        <section class="empty-card">
            <span class="empty-card__icon" aria-hidden="true">♡</span>
            <h2>Por ahora ya viste todos los perfiles</h2>
            <p>Completa tu perfil o vuelve más tarde para descubrir nuevas personas.</p>
            <a class="button" href="{{ route('profile.edit') }}">Completar mi perfil</a>
        </section>
    @else
        <section class="profile-grid" aria-label="Perfiles sugeridos">
            @foreach ($profiles as $profile)
                <article class="profile-card">
                    <div class="profile-card__photo-wrap">
                        <img class="profile-card__photo" src="{{ $profile->photo_url }}" alt="Foto de perfil de {{ $profile->first_name }}">
                        <span class="profile-card__photo-shade" aria-hidden="true"></span>
                        @if ($profile->is_demo)
                            <span class="sample-label profile-card__sample">PERFIL DE MUESTRA</span>
                        @endif
                        <div class="profile-card__title">
                            <h2>{{ $profile->first_name }}, {{ $profile->age }}</h2>
                            <p>{{ $profile->city ?: 'Cerca de ti' }}</p>
                        </div>
                    </div>
                    <div class="profile-card__content">
                        @if ($profile->gender)
                            <span class="profile-chip">{{ $profile->gender }}</span>
                        @endif
                        <p class="profile-card__bio">{{ $profile->bio ?: 'Aquí empieza una nueva conversación.' }}</p>
                        <div class="profile-card__actions">
                            <form action="{{ route('discover.action', $profile->user) }}" method="post">
                                @csrf
                                <button class="round-action round-action--pass" type="submit" name="action" value="pass"
                                    aria-label="Pasar el perfil de {{ $profile->first_name }}">×</button>
                            </form>
                            <form action="{{ route('discover.action', $profile->user) }}" method="post">
                                @csrf
                                <button class="round-action round-action--like" type="submit" name="action" value="like"
                                    aria-label="Me gusta el perfil de {{ $profile->first_name }}">♥</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif
</main>
@endsection
