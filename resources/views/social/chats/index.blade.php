@extends('layouts.app')

@section('title', 'Chats')

@section('content')
<main class="social-shell">
    <section class="page-heading">
        <div>
            <p class="eyebrow">CONVERSACIONES CON RESPETO</p>
            <h1>Tus chats</h1>
            <p>Solo puedes conversar con tus coincidencias.</p>
        </div>
    </section>

    @if ($matches->isEmpty())
        <section class="empty-card">
            <span class="empty-card__icon" aria-hidden="true">▤</span>
            <h2>Aún no tienes conversaciones</h2>
            <p>Cuando el interés sea mutuo, encontrarás aquí el espacio para hablar.</p>
            <a class="button" href="{{ route('discover.index') }}">Descubrir personas</a>
        </section>
    @else
        <section class="chat-list" aria-label="Conversaciones">
            @foreach ($matches as $match)
                @php
                    $other = $match->otherUser(auth()->id());
                    $lastMessage = $match->latestMessage;
                @endphp
                @if ($other && $other->profile)
                    <a class="chat-list-item" href="{{ route('chats.show', $match) }}">
                        <img src="{{ $other->profile->photo_url }}" alt="">
                        <span class="chat-list-item__copy">
                            <strong>{{ $other->profile->first_name }}</strong>
                            <small>{{ $lastMessage ? \Illuminate\Support\Str::limit($lastMessage->body, 70) : 'Saluda y empieza la conversación.' }}</small>
                        </span>
                        <span class="chat-list-item__arrow" aria-hidden="true">›</span>
                    </a>
                @endif
            @endforeach
        </section>
    @endif
</main>
@endsection
