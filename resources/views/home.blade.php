@extends('layouts.app')

@section('title', 'Conoce personas nuevas')

@section('content')
<main class="landing-page">
    <section class="landing-hero">
        <div class="landing-copy">
            <p class="eyebrow">CONEXIONES QUE SE SIENTEN REALES</p>
            <h1>Una buena historia puede empezar con un hola.</h1>
            <p class="landing-copy__text">Descubre personas cerca de ti, comparte intereses y conversa a tu propio ritmo.</p>
            <div class="landing-actions">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a class="button" href="{{ route('admin.support.index') }}">Ayudar a usuarios <span aria-hidden="true">→</span></a>
                    @else
                        <a class="button" href="{{ route('discover.index') }}">Descubrir personas <span aria-hidden="true">→</span></a>
                    @endif
                @else
                    <a class="button" href="{{ route('register') }}">Crear mi cuenta <span aria-hidden="true">→</span></a>
                    <a class="text-link" href="{{ route('login') }}">Ya tengo una cuenta</a>
                @endauth
            </div>
            <p class="landing-note"><span aria-hidden="true">♡</span> Un espacio para personas mayores de 18 años.</p>
        </div>

        @if ($sample)
            <div class="landing-visual" aria-label="Perfil de ejemplo">
                <span class="landing-heart landing-heart--one" aria-hidden="true">♡</span>
                <article class="preview-card">
                    <img src="{{ $sample->photo_url }}" alt="Foto de perfil de muestra">
                    <div class="preview-card__info">
                        <span class="sample-label">PERFIL DE MUESTRA</span>
                        <h2>{{ $sample->first_name }}, {{ $sample->age }}</h2>
                        <p>{{ $sample->city ?: 'Conoce personas nuevas' }} · {{ \Illuminate\Support\Str::limit($sample->bio, 42) }}</p>
                    </div>
                    <span class="preview-card__like" aria-hidden="true">♥</span>
                </article>
                <span class="landing-heart landing-heart--two" aria-hidden="true">♡</span>
            </div>
        @endif
    </section>

    <section class="landing-features">
        <div class="section-heading">
            <p class="eyebrow">FÁCIL, CERCANO Y A TU MANERA</p>
            <h2>Conoce, conecta, conversa.</h2>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-card__icon" aria-hidden="true">⌕</span>
                <h3>Descubre perfiles</h3>
                <p>Explora personas adultas, conoce sus intereses y decide con quién conectar.</p>
            </article>
            <article class="feature-card">
                <span class="feature-card__icon" aria-hidden="true">♡</span>
                <h3>Conexión mutua</h3>
                <p>Solo se abre una conversación cuando las dos personas se gustan.</p>
            </article>
            <article class="feature-card">
                <span class="feature-card__icon" aria-hidden="true">✦</span>
                <h3>Habla con respeto</h3>
                <p>Cuida tu información y contacta al equipo de ayuda si lo necesitas.</p>
            </article>
        </div>
    </section>
</main>
@endsection
