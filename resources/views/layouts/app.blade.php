<!doctype html>
<html lang="EN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">


    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @guest
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-12 col-md-3">LOGO</div>
                <div class="col-12 col-md-5"></div>
                <div class="col-12 col-md-4">
                    <a class="btn btn-outline-dark" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-outline-dark" href="{{ route('register') }}">Register</a>
                </div>
            </div>
        </div>
        @else
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-12 col-md-3">LOGO</div>
                <div class="col-12 col-md-5"></div>
                <div class="col-12 col-md-4">
                    <a class="btn btn-outline-dark" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
        @endguest

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
