@extends('layouts.app')

@section('title', 'Conversación')

@section('content')
<main class="social-shell chat-page">
    <header class="chat-header">
        <a class="back-link" href="{{ route('chats.index') }}" aria-label="Volver a tus chats">‹</a>
        <img src="{{ $other->profile->photo_url }}" alt="">
        <div>
            <h1>{{ $other->profile->first_name }}</h1>
            <small>{{ $other->profile->city ?: 'Lovematch' }}</small>
        </div>
    </header>

    <section class="message-thread" aria-label="Mensajes">
        @forelse ($messages as $message)
            <article class="message-bubble {{ $message->sender_id === auth()->id() ? 'message-bubble--mine' : 'message-bubble--theirs' }}">
                <p>{{ $message->body }}</p>
                <time datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->format('d/m H:i') }}</time>
            </article>
        @empty
            <p class="chat-welcome"><span aria-hidden="true">♡</span><br>¡Es una coincidencia! Envía un saludo para empezar.</p>
        @endforelse
    </section>

    <form class="chat-compose" action="{{ route('messages.store', $match) }}" method="post">
        @csrf
        <label class="sr-only" for="body">Escribe un mensaje</label>
        <input id="body" name="body" value="{{ old('body') }}" maxlength="1000" placeholder="Escribe un mensaje..." required>
        <button type="submit" aria-label="Enviar mensaje">➤</button>
    </form>
    @error('body') <p class="login__error">{{ $message }}</p> @enderror
</main>
@endsection
