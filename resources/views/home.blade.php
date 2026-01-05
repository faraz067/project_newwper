<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapangin - Booking Lapangan Olahraga</title>
    
    {{-- CSS Bootstrap 5 & Google Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    {{-- Material Symbols (PENTING: Agar icon sama dengan dashboard) --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

    {{-- STYLE SAMA PERSIS DENGAN DASHBOARD --}}
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f8f9fa; 
            overflow-x: hidden;
        }

        /* Gradient Warna Warni (Sama dengan Dashboard) */
        .bg-gradient-primary { background-image: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%); color: white; }
        .bg-gradient-warning { background-image: linear-gradient(310deg, #f53939 0%, #fbcf33 100%); }
        .bg-gradient-success { background-image: linear-gradient(310deg, #2dce89 0%, #2dcecc 100%); }
        .bg-gradient-danger  { background-image: linear-gradient(310deg, #ea0606 0%, #ff667c 100%); }
        .bg-gradient-info    { background-image: linear-gradient(310deg, #11cdef 0%, #1171ef 100%); }

        /* Navbar Style */
        .navbar { background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        .nav-link { font-weight: 600; color: #344767 !important; font-size: 0.9rem; }
        
        /* Card & Effects */
        .card { border: none; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); }
        .card-hover { transition: all 0.3s ease; cursor: pointer; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important; }
        
        /* Icon Shape (Kotak Icon) */
        .icon-shape { width: 48px; height: 48px; background-position: center; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; }
        .icon-lg { width: 64px; height: 64px; }

        /* STYLE KHUSUS LAPANGAN */
        .grayscale { filter: grayscale(100%); }
        .border-radius-section-top { border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .img-lapangan { height: 180px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .badge-harga { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); border-radius: 8px; }
        
        /* Text Utilities */
        .text-gradient { background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .text-primary-gradient { background-image: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%); }
    </style>
</head>
<body>

    {{-- NAVBAR GUEST (SIMPEL) --}}
    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center font-weight-bold text-dark" href="#">
                <i class="material-symbols-rounded text-primary me-2" style="font-size: 32px;">sports_tennis</i>
                Lapangin
            </a>
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link me-3" href="#pilihan-lapangan">Lihat Lapangan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link font-weight-bold me-2">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn bg-gradient-primary btn-sm mb-0 px-4 border-radius-lg">Daftar Sekarang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- WRAPPER UTAMA (Agar tidak tertutup Navbar) --}}
    <div class="container py-5 mt-5">

        {{-- ========================================== --}}
        {{-- 1. HERO SECTION (Gaya Dashboard) --}}
        {{-- ========================================== --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="card bg-gradient-primary shadow-lg position-relative overflow-hidden">
                    {{-- Dekorasi Icon Besar di Background --}}
                    <div class="position-absolute top-0 end-0 opacity-10 p-4">
                        <i class="material-symbols-rounded" style="font-size: 200px; color: white;">calendar_month</i>
                    </div>
                    <div class="card-body p-5 text-white position-relative z-index-1">
                        <h1 class="text-white font-weight-bold mb-2">Olahraga Tanpa Ribet! ⚡</h1>
                        <p class="opacity-8 mb-4 text-lg" style="max-width: 600px;">
                            Cek jadwal real-time, pilih lapangan favoritmu, dan booking dalam hitungan detik. 
                            Gabung dengan komunitas olahraga terbesar di sini.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('register') }}" class="btn btn-white text-primary font-weight-bold shadow-sm px-4 py-2">
                                <i class="material-symbols-rounded me-1 align-middle">person_add</i> Buat Akun
                            </a>
                            <a href="#pilihan-lapangan" class="btn btn-outline-white text-white font-weight-bold px-4 py-2">
                                <i class="material-symbols-rounded me-1 align-middle">visibility</i> Intip Lapangan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- 2. KEUNGGULAN (Gaya Statistik Dashboard) --}}
        {{-- ========================================== --}}
        <div class="row mb-5">
            {{-- Card 1 --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Akses Cepat</p>
                                    <h5 class="font-weight-bolder mb-0 text-dark">
                                        Real-Time
                                        <span class="text-success text-sm font-weight-bolder">Online</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md p-3 text-white">
                                    <i class="material-symbols-rounded text-lg opacity-10">update</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Harga Terbaik</p>
                                    <h5 class="font-weight-bolder mb-0 text-dark">
                                        Terjangkau
                                        <span class="text-warning text-sm font-weight-bolder">Hemat</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md p-3 text-white">
                                    <i class="material-symbols-rounded text-lg opacity-10">savings</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Fasilitas</p>
                                    <h5 class="font-weight-bolder mb-0 text-dark">
                                        Lengkap
                                        <span class="text-primary text-sm font-weight-bolder">Premium</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md p-3 text-white">
                                    <i class="material-symbols-rounded text-lg opacity-10">diamond</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- 3. DAFTAR LAPANGAN (COPY DARI DASHBOARD) --}}
        {{-- ========================================== --}}
        <div class="row mb-3" id="pilihan-lapangan">
            <div class="col-12 text-center mb-4">
                <h3 class="font-weight-bolder mb-1">🔥 Pilihan Lapangan</h3>
                <p class="text-secondary">Lihat fasilitas kami, login untuk mulai booking.</p>
            </div>
        </div>

        <div class="row mb-4">
            {{-- ITEM 1: PADEL --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-hover border-0 shadow-sm h-100" data-bs-toggle="modal" data-bs-target="#modalPadel">
                    <div class="position-relative">
                        <img src="https://nocindonesia.or.id/wp-content/uploads/2025/08/Biaya-Bangun-Lapangan-Padel-Lengkap-dengan-Rumput-Sintetis.png" class="w-100 img-lapangan" alt="Padel">
                        <span class="position-absolute top-0 end-0 m-3 py-1 px-2 text-white badge-harga text-xs font-weight-bold">Rp 150.000</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-gradient-primary text-xxs">POPULER</span>
                            <div class="d-flex align-items-center text-warning text-sm">
                                <i class="material-symbols-rounded text-sm">star</i> <span class="ms-1 font-weight-bold text-dark">4.9</span>
                            </div>
                        </div>
                        <h6 class="mb-1 text-dark font-weight-bold">Padel Court</h6>
                        <p class="text-xs text-secondary mb-0">Kaca tempered, Rumput biru.</p>
                    </div>
                </div>
            </div>

            {{-- ITEM 2: BADMINTON (RENOVASI) --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-hover border-0 shadow-sm h-100 grayscale" data-bs-toggle="modal" data-bs-target="#modalRenovasi">
                    <div class="position-relative">
                        <div class="position-absolute w-100 h-100 bg-dark opacity-4 border-radius-section-top"></div>
                        <img src="https://i0.wp.com/abouttng.com/wp-content/uploads/2022/06/gambar-01-6.jpg?fit=500%2C278&ssl=1" class="w-100 img-lapangan" alt="Badminton">
                        <span class="position-absolute top-50 start-50 translate-middle badge bg-danger shadow-lg px-3 py-2">
                            <i class="material-symbols-rounded align-middle me-1">engineering</i> SEDANG RENOVASI
                        </span>
                    </div>
                    <div class="card-body p-3 opacity-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary text-xxs">MAINTENANCE</span>
                        </div>
                        <h6 class="mb-1 text-dark font-weight-bold">Badminton Hall</h6>
                        <p class="text-xs text-secondary mb-0">Perbaikan lantai & lampu.</p>
                    </div>
                </div>
            </div>

            {{-- ITEM 3: BASKET --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-hover border-0 shadow-sm h-100" data-bs-toggle="modal" data-bs-target="#modalBasket">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-100 img-lapangan" alt="Basket">
                        <span class="position-absolute top-0 end-0 m-3 py-1 px-2 text-white badge-harga text-xs font-weight-bold">Rp 80.000</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-gradient-warning text-xxs">OUTDOOR</span>
                            <div class="d-flex align-items-center text-warning text-sm">
                                <i class="material-symbols-rounded text-sm">star</i> <span class="ms-1 font-weight-bold text-dark">4.8</span>
                            </div>
                        </div>
                        <h6 class="mb-1 text-dark font-weight-bold">Basket Court</h6>
                        <p class="text-xs text-secondary mb-0">Ring fiber & lantai halus.</p>
                    </div>
                </div>
            </div>

            {{-- ITEM 4: PINGPONG --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-hover border-0 shadow-sm h-100" data-bs-toggle="modal" data-bs-target="#modalPingpong">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1534158914592-062992fbe900?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-100 img-lapangan" alt="Pingpong">
                        <span class="position-absolute top-0 end-0 m-3 py-1 px-2 text-white badge-harga text-xs font-weight-bold">Rp 30.000</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-gradient-success text-xxs">INDOOR</span>
                            <div class="d-flex align-items-center text-warning text-sm">
                                <i class="material-symbols-rounded text-sm">star</i> <span class="ms-1 font-weight-bold text-dark">4.5</span>
                            </div>
                        </div>
                        <h6 class="mb-1 text-dark font-weight-bold">Pingpong Area</h6>
                        <p class="text-xs text-secondary mb-0">Meja ITTF Standard, AC.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- MODALS (Isinya sama, Action -> Login) --}}
    {{-- ========================================== --}}

    {{-- MODAL RENOVASI --}}
    <div class="modal fade" id="modalRenovasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div class="icon icon-lg bg-gradient-danger shadow-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="material-symbols-rounded text-white text-xxl">engineering</i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Sedang Perbaikan 🚧</h5>
                    <p class="text-sm text-secondary mb-4">Fasilitas ini sedang maintenance. Silakan login nanti untuk cek update terbaru.</p>
                    <button type="button" class="btn bg-gradient-dark w-100" data-bs-dismiss="modal">Oke</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PADEL --}}
    <div class="modal fade" id="modalPadel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Padel Court</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="https://nocindonesia.or.id/wp-content/uploads/2025/08/Biaya-Bangun-Lapangan-Padel-Lengkap-dengan-Rumput-Sintetis.png" class="w-100 border-radius-lg mb-3 shadow-sm">
                    <h4 class="text-primary font-weight-bolder">Rp 150.000 <span class="text-sm text-secondary font-weight-normal">/ jam</span></h4>
                    <p class="text-sm">Lapangan standar WPT, dinding kaca tempered aman, dan rumput sintetis premium.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    {{-- Action Redirect ke Register --}}
                    <a href="{{ route('register') }}" class="btn bg-gradient-primary">Login untuk Booking 🔒</a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL BASKET --}}
    <div class="modal fade" id="modalBasket" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Basket Court</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-100 border-radius-lg mb-3 shadow-sm">
                    <h4 class="text-primary font-weight-bolder">Rp 80.000 <span class="text-sm text-secondary font-weight-normal">/ jam</span></h4>
                    <p class="text-sm">Lapangan outdoor dengan lantai beton yang sudah dicat epoxy. Ring basket fiber standar kompetisi.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('register') }}" class="btn bg-gradient-primary">Login untuk Booking 🔒</a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PINGPONG --}}
    <div class="modal fade" id="modalPingpong" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Pingpong Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="https://images.unsplash.com/photo-1534158914592-062992fbe900?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-100 border-radius-lg mb-3 shadow-sm">
                    <h4 class="text-primary font-weight-bolder">Rp 30.000 <span class="text-sm text-secondary font-weight-normal">/ jam</span></h4>
                    <p class="text-sm">Area ber-AC yang nyaman. Meja kualitas ITTF. Bet dan bola tersedia.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('register') }}" class="btn bg-gradient-primary">Login untuk Booking 🔒</a>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER SEDERHANA --}}
    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="text-muted text-sm mb-0">© 2026 Lapangin. All rights reserved.</p>
        </div>
    </footer>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>