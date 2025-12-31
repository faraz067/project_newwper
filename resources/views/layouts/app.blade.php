<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Lapangin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Material Kit CSS -->
    <link href="{{ asset('assets/css/material-kit.css?v=3.1.0') }}" rel="stylesheet" />

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">

    @stack('styles')
</head>
<body class="index-page bg-gray-200">

    {{-- NAVBAR --}}
    @include('layouts.navbar')

    {{-- PAGE CONTENT --}}
    @yield('content')

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/countup.min.js') }}"></script>
    <script src="{{ asset('assets/js/material-kit.min.js?v=3.1.0') }}"></script>

    @stack('scripts')
</body>
</html>
