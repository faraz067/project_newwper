<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('admin.dashboard') }}">
            {{-- Pastikan path gambarnya benar --}}
            <img src="{{ asset('admin_assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Admin Dashboard</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            
            {{-- MENU DASHBOARD --}}
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            {{-- === MENU BARU: DATA PENGGUNA === --}}
            {{-- Menggunakan icon fa-users dan warna hijau (success) --}}
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Pengguna</span>
                </a>
            </li>

            {{-- MENU BOOKING --}}
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.bookings*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Booking</span>
                </a>
            </li>

            {{-- MENU DATA LAPANGAN --}}
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/courts*') ? 'active' : '' }}" href="{{ route('admin.courts.index') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Lapangan</span>
                </a>
            </li>

        </ul>
    </div>
    
    <div class="sidenav-footer mx-3 ">
        <div class="card card-plain shadow-none" id="sidenavCard">
            {{-- Pastikan path gambarnya benar --}}
            <img class="w-50 mx-auto" src="{{ asset('admin_assets/img/illustrations/icon-documentation.svg') }}" alt="sidebar_illustration">
            <div class="card-body text-center p-3 w-100 pt-0">
                <div class="docs-info">
                    <h6 class="mb-0">Butuh Bantuan?</h6>
                    <p class="text-xs font-weight-bold mb-0">Hubungi Developer</p>
                </div>
            </div>
        </div>
    </div>
</aside>