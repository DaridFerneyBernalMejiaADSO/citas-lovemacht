@extends('layouts.app')

@section('title', 'Coincidencias')

@section('content')
<main class="social-shell">
    <section class="page-heading">
        <div>
            <p class="eyebrow">EL INTERÉS ES MUTUO</p>
            <h1>Tus coincidencias</h1>
            <p>Cuando dos personas se gustan, pueden empezar a conversar.</p>
        </div>
        <span class="age-safety"><span aria-hidden="true">♡</span> {{ $matches->count() }} conexiones</span>
    </section>

    @if ($matches->isEmpty())
        <section class="empty-card">
            <span class="empty-card__icon" aria-hidden="true">♡</span>
            <h2>Tu primera coincidencia puede estar cerca</h2>
            <p>Explora perfiles y da un me gusta a las personas que te interesen.</p>
            <a class="button" href="{{ route('discover.index') }}">Descubrir personas</a>
        </section>
    @else
        <section class="match-grid" aria-label="Tus coincidencias">
            @foreach ($matches as $match)
                @php($other = $match->otherUser(auth()->id()))
                @if ($other && $other->profile)
                    <article class="match-card">
                        <img src="{{ $other->profile->photo_url }}" alt="Foto de {{ $other->profile->first_name }}">
                        <div class="match-card__body">
                            <h2>{{ $other->profile->first_name }}, {{ $other->profile->age }}</h2>
                            <p>{{ $other->profile->city ?: 'Cerca de ti' }}</p>
                            <a class="button button--small" href="{{ route('chats.show', $match) }}">Enviar mensaje</a>
                        </div>
                    </article>
                @endif
            @endforeach
        </section>
    @endif
</main>
@endsection
