<div class="container position-sticky z-index-sticky top-0">
  <div class="row">
    <div class="col-12">

      <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-fixed shadow position-absolute my-3 p-2 start-0 end-0 mx-4">
        <div class="container-fluid px-0">

          <a class="navbar-brand font-weight-bolder ms-sm-3 text-sm" href="{{ url('/') }}">
            BookingLapangan
          </a>

          <button class="navbar-toggler shadow-none ms-2" type="button"
            data-bs-toggle="collapse" data-bs-target="#navigation"
            aria-controls="navigation" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
              <span class="navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>
          </button>

          <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ms-auto">

              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center font-weight-semibold" href="{{ url('/') }}">
                  <i class="material-symbols-rounded opacity-6 me-2 text-md">home</i>
                  Home
                </a>
              </li>

              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center font-weight-semibold" href="{{ url('/lapangan') }}">
                  <i class="material-symbols-rounded opacity-6 me-2 text-md">sports_soccer</i>
                  Lapangan
                </a>
              </li>

              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center font-weight-semibold" href="{{ url('/jadwal') }}">
                  <i class="material-symbols-rounded opacity-6 me-2 text-md">calendar_month</i>
                  Jadwal
                </a>
              </li>

              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center font-weight-semibold" 
                   href="#" 
                   data-bs-toggle="modal" 
                   data-bs-target="#modalCaraBooking">
                  <i class="material-symbols-rounded opacity-6 me-2 text-md">help</i>
                  Cara Booking
                </a>
              </li>

              <li class="nav-item mx-2">
                <a class="nav-link d-flex align-items-center font-weight-semibold" href="{{ url('/tentang') }}">
                  <i class="material-symbols-rounded opacity-6 me-2 text-md">info</i>
                  Tentang
                </a>
              </li>

              @guest
              <li class="nav-item dropdown dropdown-hover mx-2">
                <a class="nav-link ps-2 d-flex cursor-pointer align-items-center font-weight-semibold"
                  id="dropdownAuth" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="material-symbols-rounded opacity-6 me-2 text-md">account_circle</i>
                  Akun
                  <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-2">
                </a>

                <div class="dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-xl mt-0 mt-lg-3"
                  aria-labelledby="dropdownAuth">
                  <a href="{{ route('login') }}" class="dropdown-item border-radius-md">
                    Sign In
                  </a>
                  <a href="{{ route('register') }}" class="dropdown-item border-radius-md">
                    Daftar
                  </a>
                </div>
              </li>
              @endguest


              @auth
              <li class="nav-item dropdown dropdown-hover mx-2">
                <a class="nav-link ps-2 d-flex cursor-pointer align-items-center font-weight-semibold"
                  id="dropdownUser" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="material-symbols-rounded opacity-6 me-2 text-md">account_circle</i>
                  {{ Auth::user()->name }}
                  <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-2">
                </a>

                <div class="dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-xl mt-0 mt-lg-3"
                  aria-labelledby="dropdownUser">

                  <a href="{{ url('/dashboard') }}" class="dropdown-item border-radius-md">
                    Dashboard
                  </a>

                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item border-radius-md text-danger">
                      Logout
                    </button>
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
                    <p class="small text-muted mb-0">Cari lapangan yang tersedia di halaman utama dan klik 'Booking'.</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">2</h1>
                    <h6 class="fw-bold">Isi Detail & Bayar</h6>
                    <p class="small text-muted mb-0">Tentukan jam main, lalu lakukan pembayaran.</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-3">
                <div class="bg-light p-3 rounded h-100">
                    <h1 class="text-primary mb-2">3</h1>
                    <h6 class="fw-bold">Mainkan!</h6>
                    <p class="small text-muted mb-0">Tunjukkan bukti booking ke petugas lapangan.</p>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3 mb-0 text-white border-0" style="background-color: #17a2b8;">
            <i class="material-symbols-rounded me-1" style="vertical-align: middle;">info</i> 
            Jika butuh bantuan, hubungi Admin via WhatsApp.
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>