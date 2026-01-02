@extends('layouts.app') 

@section('content')
<div class="container-fluid py-4">

    {{-- ========================================== --}}
    {{-- 1. SECTION SAMBUTAN & HERO --}}
    {{-- ========================================== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-lg border-0 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-10 p-4">
                    <i class="material-symbols-rounded" style="font-size: 150px; color: white;">sports_tennis</i>
                </div>
                <div class="card-body p-4 text-white position-relative z-index-1">
                    <h2 class="text-white mb-1">Halo, {{ Auth::user()->name }}! 👋</h2>
                    <p class="opacity-8 mb-4">Siap smash hari ini? Cek jadwal main kamu di bawah ini.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('booking.create') }}" class="btn btn-light text-primary font-weight-bold shadow-sm">
                            <i class="material-symbols-rounded me-1 align-middle">add_circle</i> Booking Baru
                        </a>
                        <a href="#area-jadwal" class="btn btn-outline-white text-white font-weight-bold">
                            <i class="material-symbols-rounded me-1 align-middle">calendar_month</i> Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 2. SECTION STATISTIK --}}
    {{-- ========================================== --}}
    <div class="row mb-4">
        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Booking Aktif</p>
                                <h4 class="font-weight-bolder mb-0">
                                    {{ isset($activeBookings) ? $activeBookings->count() : 0 }} 
                                    <span class="text-success text-sm font-weight-bolder">Jadwal</span>
                                </h4>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md p-3 text-white">
                                <i class="material-symbols-rounded text-lg opacity-10">calendar_clock</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Total Bermain</p>
                                <h4 class="font-weight-bolder mb-0">
                                    {{ $totalMain ?? 0 }} 
                                    <span class="text-primary text-sm font-weight-bolder">Kali</span>
                                </h4>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md p-3 text-white">
                                <i class="material-symbols-rounded text-lg opacity-10">emoji_events</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 3. TABEL JADWAL & RIWAYAT --}}
    {{-- ========================================== --}}
    <div class="row mb-4" id="area-jadwal">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header pb-0 p-3 bg-white">
                    <h6 class="mb-2 font-weight-bold">📅 Jadwal Main Kamu (Upcoming)</h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lapangan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Waktu</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($activeBookings) && $activeBookings->count() > 0)
                                    @foreach($activeBookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <div class="avatar avatar-sm me-3 bg-gradient-primary rounded-circle text-center d-flex align-items-center justify-content-center">
                                                        <i class="material-symbols-rounded text-white text-xs">sports_tennis</i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $booking->field->name ?? 'Lapangan #'.$booking->court_id }}</h6>
                                                    <p class="text-xs text-secondary mb-0">ID: #{{ $booking->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('l, d M Y') }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @if($booking->status == 'confirmed')
                                                <span class="badge badge-sm bg-gradient-success">Confirmed</span>
                                            @elseif($booking->status == 'pending')
                                                <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-secondary">{{ $booking->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 32px;">event_available</i>
                                                <h6 class="text-secondary text-sm">Belum ada jadwal main.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header pb-0 p-3 bg-white">
                    <h6 class="mb-0 font-weight-bold">📜 Riwayat Terakhir</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @if(isset($historyBookings) && $historyBookings->count() > 0)
                            @foreach($historyBookings as $history)
                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-rounded text-sm">history</i>
                                    </button>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{ $history->field->name ?? 'Lapangan #'.$history->court_id }}</h6>
                                        <span class="text-xs text-secondary">
                                            {{ \Carbon\Carbon::parse($history->start_time)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold">
                                    Selesai
                                </div>
                            </li>
                            @endforeach
                        @else
                            <li class="list-group-item border-0 text-center text-secondary text-sm py-4">
                                Belum ada riwayat bermain.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 4. DAFTAR PILIHAN LAPANGAN --}}
    {{-- ========================================== --}}
    <div class="row mt-4 mb-3">
        <div class="col-12">
            <h5 class="font-weight-bolder mb-0">🔥 Pilihan Lapangan</h5>
            <p class="text-sm text-muted">Fasilitas terbaik untuk performa maksimal.</p>
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

        {{-- ITEM 2: BADMINTON (STATUS: RENOVASI) --}}
        {{-- Target Modal Mengarah ke #modalRenovasi --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-hover border-0 shadow-sm h-100 grayscale" data-bs-toggle="modal" data-bs-target="#modalRenovasi">
                <div class="position-relative">
                    {{-- Overlay Gelap --}}
                    <div class="position-absolute w-100 h-100 bg-dark opacity-4 border-radius-section-top"></div>
                    
                    {{-- Gambar Baru (Tetap dipakai) --}}
                    <img src="https://i0.wp.com/abouttng.com/wp-content/uploads/2022/06/gambar-01-6.jpg?fit=500%2C278&ssl=1" class="w-100 img-lapangan" alt="Badminton">
                    
                    {{-- Badge Renovasi --}}
                    <span class="position-absolute top-50 start-50 translate-middle badge bg-danger shadow-lg px-3 py-2">
                        <i class="material-symbols-rounded align-middle me-1">engineering</i> SEDANG RENOVASI
                    </span>
                </div>
                <div class="card-body p-3 opacity-6"> {{-- Opacity agar terlihat non-aktif --}}
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

    {{-- ========================================== --}}
    {{-- MODAL POPUPS --}}
    {{-- ========================================== --}}

    {{-- MODAL KHUSUS RENOVASI (Pop Up Peringatan) --}}
    <div class="modal fade" id="modalRenovasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div class="icon icon-lg bg-gradient-danger shadow-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="material-symbols-rounded text-white text-xxl">engineering</i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Sedang Perbaikan 🚧</h5>
                    <p class="text-sm text-secondary mb-4">
                        Mohon maaf, fasilitas ini sedang dalam proses maintenance demi kenyamanan bersama. Silakan pilih lapangan lain ya!
                    </p>
                    <button type="button" class="btn bg-gradient-dark w-100" data-bs-dismiss="modal">Oke, Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PADEL --}}
    <div class="modal fade" id="modalPadel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Padel Court</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="https://nocindonesia.or.id/wp-content/uploads/2025/08/Biaya-Bangun-Lapangan-Padel-Lengkap-dengan-Rumput-Sintetis.png" class="w-100 border-radius-lg mb-3 shadow-sm">
                    <h4 class="text-primary font-weight-bolder">Rp 150.000 <span class="text-sm text-secondary font-weight-normal">/ jam</span></h4>
                    <p class="text-sm">Lapangan standar WPT (World Padel Tour), dinding kaca tempered aman, dan rumput sintetis premium.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('booking.create') }}" class="btn bg-gradient-primary">Booking Padel 🎾</a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL BASKET --}}
    <div class="modal fade" id="modalBasket" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Basket Court</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-100 border-radius-lg mb-3 shadow-sm">
                    <h4 class="text-primary font-weight-bolder">Rp 80.000 <span class="text-sm text-secondary font-weight-normal">/ jam</span></h4>
                    <p class="text-sm">Lapangan outdoor dengan lantai beton yang sudah dicat epoxy. Ring basket fiber standar kompetisi.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('booking.create') }}" class="btn bg-gradient-primary">Booking Basket 🏀</a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PINGPONG --}}
    <div class="modal fade" id="modalPingpong" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Pingpong Area</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="https://images.unsplash.com/photo-1534158914592-062992fbe900?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-100 border-radius-lg mb-3 shadow-sm">
                    <h4 class="text-primary font-weight-bolder">Rp 30.000 <span class="text-sm text-secondary font-weight-normal">/ jam</span></h4>
                    <p class="text-sm">Area ber-AC yang nyaman. Meja kualitas ITTF (International Table Tennis Federation). Bet dan bola tersedia.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('booking.create') }}" class="btn bg-gradient-primary">Booking Pingpong 🏓</a>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- STYLE TAMBAHAN --}}
<style>
    /* Gradient Warna Warni */
    .bg-gradient-primary { background-image: linear-gradient(310deg, #5e72e4 0%, #825ee4 100%); color: white; }
    .bg-gradient-warning { background-image: linear-gradient(310deg, #f53939 0%, #fbcf33 100%); }
    .bg-gradient-success { background-image: linear-gradient(310deg, #2dce89 0%, #2dcecc 100%); }
    .bg-gradient-danger  { background-image: linear-gradient(310deg, #ea0606 0%, #ff667c 100%); }
    
    /* Efek Hover Keren */
    .card-hover { transition: all 0.3s ease; cursor: pointer; }
    .card-hover:hover { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    
    /* STYLE KHUSUS RENOVASI */
    .grayscale { 
        filter: grayscale(100%); 
        pointer-events: auto;
    }
    .border-radius-section-top {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    /* Gambar Lapangan */
    .img-lapangan { height: 160px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
    
    /* Badge Harga */
    .badge-harga { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); border-radius: 8px; }
    
    /* Umum */
    .card { border-radius: 1rem; }
</style>

@endsection