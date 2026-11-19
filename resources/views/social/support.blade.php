@extends('layouts.app')

@section('title', 'Ayuda')

@section('content')
<main class="social-shell">
    <section class="page-heading">
        <div>
            <p class="eyebrow">ESTAMOS PARA AYUDARTE</p>
            <h1>Centro de ayuda</h1>
            <p>Cuéntanos qué sucede. El equipo administrador responderá tu solicitud.</p>
        </div>
    </section>

    <div class="help-layout">
        <section class="form-card">
            <h2>Nueva solicitud</h2>
            <form class="stack-form" action="{{ route('support.store') }}" method="post">
                @csrf
                <label>Asunto
                    <input name="subject" value="{{ old('subject') }}" maxlength="120" required>
                    @error('subject') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <label>¿Cómo podemos ayudarte?
                    <textarea name="message" rows="6" maxlength="2000" required>{{ old('message') }}</textarea>
                    @error('message') <span class="login__error">{{ $message }}</span> @enderror
                </label>
                <button class="button" type="submit">Enviar solicitud</button>
            </form>
        </section>

        <section class="support-history">
            <h2>Mis solicitudes</h2>
            @forelse ($requests as $request)
                <article class="support-card">
                    <div class="support-card__top">
                        <h3>{{ $request->subject }}</h3>
                        <span class="status-pill">{{ ucfirst($request->status) }}</span>
                    </div>
                    <p>{{ $request->message }}</p>
                    <small class="muted-copy">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                    @if ($request->admin_response)
                        <div class="support-response">
                            <strong>Respuesta del equipo</strong>
                            <p>{{ $request->admin_response }}</p>
                        </div>
                    @endif
                </article>
            @empty
                <p class="muted-copy">Todavía no tienes solicitudes de ayuda.</p>
            @endforelse
        </section>
    </div>
</main>
@endsection
