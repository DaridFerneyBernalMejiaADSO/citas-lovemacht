<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#5b087e">
    <title>@yield('title', 'Lovematch') | Lovematch</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a href="{{ route('home') }}" class="brand-mark" aria-label="Lovematch, inicio">
                <span class="brand-mark__heart" aria-hidden="true">♡</span>
                <span>Lovematch</span>
            </a>
            <nav class="primary-nav" aria-label="Navegación principal">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.support.index') }}">Ayuda a usuarios</a>
                    @else
                        <a href="{{ route('discover.index') }}">Descubre</a>
                        <a href="{{ route('matches.index') }}">Coincidencias</a>
                        <a href="{{ route('chats.index') }}">Chats</a>
                        <a href="{{ route('support.index') }}">Ayuda</a>
                        <a href="{{ route('profile.edit') }}">Mi perfil</a>
                    @endif
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="nav-logout" type="submit">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                    <a class="button button--small" href="{{ route('register') }}">Crear cuenta</a>
                @endauth
            </nav>
        </div>
    </header>

    @if (session('status'))
        <div class="social-shell"><div class="notice" role="status">{{ session('status') }}</div></div>
    @endif

    @yield('content')

    @auth
        @if (auth()->user()->role === 'admin')
            <nav class="mobile-nav mobile-nav--admin" aria-label="Navegación móvil">
                <a href="{{ route('admin.support.index') }}"><span aria-hidden="true">✦</span><small>Ayuda</small></a>
                <form action="{{ route('logout') }}" method="post">@csrf<button type="submit"><span aria-hidden="true">↪</span><small>Salir</small></button></form>
            </nav>
        @else
            <nav class="mobile-nav" aria-label="Navegación móvil">
                <a href="{{ route('discover.index') }}"><span aria-hidden="true">⌕</span><small>Descubre</small></a>
                <a href="{{ route('matches.index') }}"><span aria-hidden="true">♡</span><small>Me gusta</small></a>
                <a href="{{ route('chats.index') }}"><span aria-hidden="true">▤</span><small>Chats</small></a>
                <a href="{{ route('support.index') }}"><span aria-hidden="true">?</span><small>Ayuda</small></a>
                <a href="{{ route('profile.edit') }}"><span aria-hidden="true">●</span><small>Perfil</small></a>
            </nav>
        @endif
    @endauth
</body>
</html>
