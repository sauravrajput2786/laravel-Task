<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="topbar__inner">
            <span class="topbar__brand">Laravel Task</span>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="topbar__logout-form">
                    @csrf
                    <button type="submit" class="btn btn--ghost btn--small">Logout</button>
                </form>
            @endauth
        </div>
    </header>

    <main class="page">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
