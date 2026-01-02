<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Judul Halaman Dinamis --}}
    <title>@yield('title', 'Lapangin - Booking Lapangan')</title>

    <link href="{{ asset('assets/css/material-kit.css?v=3.1.0') }}" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Style Tambahan dari halaman lain --}}
    @stack('styles')

    <style>
        /* Perbaikan agar konten tidak tertutup Navbar Fixed */
        body {
            padding-top: 80px; 
        }
        /* Style dropdown user di navbar agar lebih rapi */
        .dropdown-menu {
            margin-top: 10px !important;
        }
    </style>
</head>
<body class="index-page bg-gray-200">

    {{-- NAVBAR --}}
    {{-- Pastikan kode navbar tadi kamu simpan di file: resources/views/layouts/navbar/main.blade.php --}}
    {{-- ATAU jika navbar langsung ditulis di sini, ganti baris ini dengan kode navbar lengkap --}}
    @include('layouts.navbar.main')

    {{-- KONTEN UTAMA --}}
    <main class="flex-shrink-0">
        @yield('content')
    </main>

    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    
    <script src="{{ asset('assets/js/material-kit.min.js?v=3.1.0') }}"></script>

    {{-- Script Tambahan dari halaman lain --}}
    @stack('scripts')

    <div class="modal fade" id="modalCaraBooking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="exampleModalLabel">📚 Panduan Cara Booking</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">1</h1>
                    <h6 class="fw-bold">Pilih Lapangan</h6>
                    <p class="small text-muted">Cari lapangan yang tersedia di halaman utama dan klik 'Booking'.</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">2</h1>
                    <h6 class="fw-bold">Isi Detail & Bayar</h6>
                    <p class="small text-muted">Tentukan jam main, lalu lakukan pembayaran sesuai nominal.</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">3</h1>
                    <h6 class="fw-bold">Mainkan!</h6>
                    <p class="small text-muted">Tunjukkan bukti booking ke petugas dan selamat bermain!</p>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0">
            <i class="fas fa-info-circle me-1"></i> 
            Jika butuh bantuan, hubungi Admin via WhatsApp.
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <a href="/" class="btn btn-primary">Mulai Booking Sekarang</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>