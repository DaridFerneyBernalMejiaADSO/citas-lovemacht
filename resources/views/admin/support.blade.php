@extends('layouts.app')

@section('title', 'Ayuda a usuarios')

@section('content')
<main class="social-shell">
    <section class="page-heading">
        <div>
            <p class="eyebrow">PANEL DE ADMINISTRACIÓN</p>
            <h1>Ayuda a usuarios</h1>
            <p>Revisa las solicitudes y responde con respeto. Este es el único panel administrativo.</p>
        </div>
        <span class="admin-badge">Administrador</span>
    </section>

    <section class="support-admin-list">
        @forelse ($requests as $request)
            <article class="support-card">
                <div class="support-card__top">
                    <h2>{{ $request->subject }}</h2>
                    <span class="status-pill">{{ ucfirst($request->status) }}</span>
                </div>
                <p class="support-requester">
                    {{ $request->user->profile?->first_name ?? $request->user->user_name }}
                    <span>{{ $request->user->email }} · {{ $request->created_at->format('d/m/Y H:i') }}</span>
                </p>
                <p>{{ $request->message }}</p>
                <form class="stack-form support-reply" action="{{ route('admin.support.update', $request) }}" method="post">
                    @csrf
                    @method('patch')
                    <label>Respuesta
                        <textarea name="admin_response" rows="3" maxlength="2000" required>{{ old('admin_response', $request->admin_response) }}</textarea>
                        @error('admin_response') <span class="login__error">{{ $message }}</span> @enderror
                    </label>
                    <label>Estado
                        <select name="status" required>
                            @foreach (['respondido' => 'Respondido', 'cerrado' => 'Cerrado'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $request->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="login__error">{{ $message }}</span> @enderror
                    </label>
                    <button class="button button--small" type="submit">Guardar respuesta</button>
                </form>
            </article>
        @empty
            <section class="empty-card">
                <span class="empty-card__icon" aria-hidden="true">✦</span>
                <h2>No hay solicitudes pendientes</h2>
                <p>Cuando alguien necesite ayuda, aparecerá aquí.</p>
            </section>
        @endforelse
        {{ $requests->links() }}
    </section>
</main>
@endsection
