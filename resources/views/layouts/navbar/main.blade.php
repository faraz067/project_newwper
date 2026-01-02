<!-- Navbar -->
<div class="container position-sticky z-index-sticky top-0">
  <div class="row">
    <div class="col-12">

      <nav class="navbar navbar-expand-lg blur border-radius-xl shadow p-3 my-3">

        <div class="container-fluid px-0">

          <!-- Brand -->
          <a class="navbar-brand font-weight-bolder ms-sm-3 text-sm" href="{{ url('/') }}">
            BookingLapangan
          </a>

          <!-- Toggle -->
          <button class="navbar-toggler shadow-none ms-2" type="button"
            data-bs-toggle="collapse" data-bs-target="#navigation">
            <span class="navbar-toggler-icon mt-2">
              <span class="navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>
          </button>

          <!-- Menu -->
          <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ms-auto">

              {{-- MENU UMUM --}}
              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center" href="{{ url('/') }}">
                  <i class="material-symbols-rounded me-2">home</i> Home
                </a>
              </li>

              {{-- USER --}}
              @role('user')
              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center" href="{{ route('booking.history') }}">
                  <i class="material-symbols-rounded me-2">history</i> Riwayat
                </a>
              </li>
              @endrole

              {{-- STAFF --}}
              @role('staff')
              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center" href="/staff/dashboard">
                  <i class="material-symbols-rounded me-2">badge</i> Staff
                </a>
              </li>
              @endrole

              {{-- ADMIN --}}
              @role('admin')
              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center" href="/admin/dashboard">
                  <i class="material-symbols-rounded me-2">admin_panel_settings</i> Admin
                </a>
              </li>
              @endrole

              {{-- GUEST --}}
              @guest
              <li class="nav-item dropdown mx-2">
                <a class="nav-link d-flex align-items-center cursor-pointer"
                   data-bs-toggle="dropdown">
                  <i class="material-symbols-rounded me-2">account_circle</i> Akun
                </a>
                <div class="dropdown-menu dropdown-menu-end p-3 border-radius-xl">
                  <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                  <a href="{{ route('register') }}" class="dropdown-item">Daftar</a>
                </div>
              </li>
              @endguest

              {{-- AUTH --}}
              @auth
              <li class="nav-item dropdown mx-2">
                <a class="nav-link d-flex align-items-center cursor-pointer"
                   data-bs-toggle="dropdown">
                  <i class="material-symbols-rounded me-2">account_circle</i>
                  {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-end p-3 border-radius-xl">
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger">Logout</button>
                  </form>
                </div>
              </li>
              @endauth

            </ul>
          </div>

        </div>
      </nav>

    </div>
  </div>
</div>
