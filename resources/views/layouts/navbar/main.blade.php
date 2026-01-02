<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        {{-- LOGO / BRAND --}}
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
            <i class="fas fa-table-tennis me-2"></i> Lapangin
        </a>

        {{-- TOMBOL MENU HP (Hamburger) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            
            {{-- MENU KIRI --}}
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
                        Home
                    </a>
                </li>
                
                {{-- MENU CARA BOOKING (POP-UP) --}}
                <li class="nav-item">
                    <a class="nav-link cursor-pointer" href="#" data-bs-toggle="modal" data-bs-target="#modalCaraBooking">
                       <i class="material-symbols-rounded opacity-6 me-2 text-md">help</i>
                       Cara Booking
                    </a>
                </li>

                {{-- MENU TAMBAH BOOKING --}}
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-sm btn-primary rounded-pill px-3 mt-1 mt-lg-0" href="{{ route('booking.create') }}">
                        <i class="fas fa-plus me-1"></i> Booking Baru
                    </a>
                </li>
            </ul>

            {{-- MENU KANAN (PROFIL DROPDOWN) --}}
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            
                            {{-- FOTO PROFIL KECIL --}}
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #ddd;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white me-2" style="width: 35px; height: 35px;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            
                            {{-- NAMA USER --}}
                            <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                        </a>

                        {{-- ISI DROPDOWN --}}
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="navbarDropdown">
                            
                            {{-- EDIT PROFIL --}}
                            <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit me-2 text-primary"></i> Edit Profil
                            </a>

                            {{-- RIWAYAT --}}
                            <a class="dropdown-item py-2" href="{{ route('booking.history') }}">
                                <i class="fas fa-history me-2 text-info"></i> Riwayat Booking
                            </a>

                            <hr class="dropdown-divider">

                            {{-- LOGOUT (VERSI FINAL - ANTI ERROR 405) --}}
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start border-0 bg-transparent">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>

                        </div>
                    </li>
                @else
                    {{-- JIKA BELUM LOGIN --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="modalCaraBooking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold text-white" id="exampleModalLabel">📚 Panduan Cara Booking</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">1</h1>
                    <h6 class="fw-bold">Pilih Lapangan</h6>
                    <p class="small text-muted mb-0">Cari lapangan dan klik 'Booking'.</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">2</h1>
                    <h6 class="fw-bold">Bayar</h6>
                    <p class="small text-muted mb-0">Tentukan jam dan lakukan pembayaran.</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">3</h1>
                    <h6 class="fw-bold">Mainkan!</h6>
                    <p class="small text-muted mb-0">Tunjukkan bukti booking ke petugas.</p>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0 border-0" style="background-color: #e0f7fa; color: #006064;">
            <i class="fas fa-info-circle me-1"></i> 
            Jika butuh bantuan, hubungi Admin via WhatsApp.
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>