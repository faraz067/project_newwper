<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auth - Booking Lapangan</title>

    <!-- Google Material Symbols -->
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />

    <!-- Material Kit CSS -->
    <link href="{{ asset('assets/css/material-kit.css') }}" rel="stylesheet">
</head>

<body class="sign-in-basic">

    {{-- Konten auth (login / register) --}}
    @yield('content')

    <!-- JS -->
    <script src="{{ asset('assets/js/material-kit.min.js') }}"></script>
</body>
</html>
