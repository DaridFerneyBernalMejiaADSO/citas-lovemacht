<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#5b087e">
    <title>@yield('title', 'Acceso') | Lovematch</title>
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>
<body>
    <main class="auth-page">
        <section class="auth-card @yield('card-class')">
            <a class="auth-brand" href="{{ route('home') }}"><span aria-hidden="true">♡</span> Lovematch</a>
            @yield('content')
        </section>
    </main>
</body>
</html>
