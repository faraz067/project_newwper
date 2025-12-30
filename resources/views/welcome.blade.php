<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOR Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 100vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .feature-icon { font-size: 3rem; color: #0d6efd; margin-bottom: 1rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">GOR SPORT</a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->role == 'staff')
                             <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-light btn-sm">Dashboard Staff</a>
                        @else
                             <a href="{{ route('booking.history') }}" class="btn btn-outline-light btn-sm">Akun Saya</a>
                        @endif
                    @else
                        <a href="/test-login-user" class="btn btn-primary btn-sm me-2">Login User</a>
                        <a href="/test-login-staff" class="btn btn-outline-light btn-sm">Login Staff</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3">Sewa Lapangan Jadi Mudah</h1>
            <p class="lead mb-4">Futsal, Badminton, Basket. Cek jadwal, booking online, langsung main!</p>
            
            @auth
                <a href="{{ route('booking.create') }}" class="btn btn-primary btn-lg px-5 py-3 fw-bold">Booking Sekarang 🚀</a>
            @else
                <a href="/test-login-user" class="btn btn-primary btn-lg px-5 py-3 fw-bold">Mulai Booking</a>
            @endauth
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white shadow-sm rounded">
                        <div class="feature-icon">⚽</div>
                        <h4>Futsal Rumput Sintetis</h4>
                        <p>Lapangan standar internasional dengan rumput sintetis berkualitas.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white shadow-sm rounded">
                        <div class="feature-icon">🏸</div>
                        <h4>Badminton Indoor</h4>
                        <p>Lantai karpet vinyl anti-slip dan pencahayaan terang.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white shadow-sm rounded">
                        <div class="feature-icon">⚡</div>
                        <h4>Booking Cepat</h4>
                        <p>Tanpa antri, tanpa ribet. Cukup klik dan lapangan siap dipakai.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>