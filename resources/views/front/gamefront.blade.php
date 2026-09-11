<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS (Latest 5.3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS (replace blocked kit) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Laravel Mix Compiled CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/front.css') }}">

    <title>@yield('title')</title>
    @yield('cs')
</head>

<body style="background-color: #0b1a2a;">

    <!-- Navbar -->
    <header>
        @include('layouts.navbar')
    </header>

    <!-- Page Content -->
    <main class="container my-4">
        @yield('Content')
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        @include('layouts.footer')
    </footer>

    <!-- Bootstrap Bundle JS (Latest) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('js')
</body>
</html>
