@extends('layouts.admin')

@section('content')
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Dashboard Admin</h6>
        </nav>
      </div>
    </nav>

    <div class="container-fluid py-4">
      
      {{-- ROW 1: STATISTIK KARTU --}}
      <div class="row">
        {{-- KARTU 1: BOOKING AKTIF (LINKED) --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <a href="{{ route('admin.bookings.index') }}" class="text-decoration-none">
                <div class="card move-on-hover">
                    <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold text-dark">Booking Aktif</p>
                            <h5 class="font-weight-bolder text-dark">{{ $activeBookings ?? 0 }}</h5>
                            <p class="mb-0 text-secondary"><span class="text-danger text-sm font-weight-bolder">Klik</span> untuk detail</p>
                        </div>
                        </div>
                        <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                            <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- KARTU 2: USER BARU --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">User Baru (Bulan Ini)</p>
                    <h5 class="font-weight-bolder">+{{ number_format($newUsers ?? 0) }}</h5>
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">User</span> terdaftar</p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- KARTU 3: MENUNGGU KONFIRMASI (YANG TADINYA DUPLIKAT, SAYA GANTI JADI PENDING) --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Perlu Konfirmasi</p>
                    {{-- Pastikan di Controller kirim variabel $pendingCount --}}
                    <h5 class="font-weight-bolder">{{ $pendingCount ?? 0 }}</h5>
                    <p class="mb-0"><span class="text-warning text-sm font-weight-bolder">Pending</span> request</p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i class="ni ni-bell-55 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- KARTU 4: TOTAL SALES --}}
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Sales</p>
                    <h5 class="font-weight-bolder">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</h5>
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">All Time</span> revenue</p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ROW 2: CHART PENDAPATAN & CAROUSEL --}}
      <div class="row mt-4">
        
        {{-- LINE CHART (PENDAPATAN) --}}
        <div class="col-lg-7 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Grafik Pendapatan {{ date('Y') }}</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-arrow-up text-success"></i>
                <span class="font-weight-bold">Update</span> Realtime
              </p>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-income" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        
        {{-- CAROUSEL LAPANGAN --}}
        <div class="col-lg-5">
          <div class="card card-carousel overflow-hidden h-100 p-0">
            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
              <div class="carousel-inner border-radius-lg h-100">
                @forelse($courts ?? [] as $key => $court)
                    <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}" style="background-image: url('{{ $court->photo ? asset('storage/' . $court->photo) : asset('admin_assets/img/carousel-1.jpg') }}'); background-size: cover;">
                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                            <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                <i class="ni ni-camera-compact text-dark opacity-10"></i>
                            </div>
                            <h5 class="text-white mb-1">{{ $court->name }}</h5>
                            <p>
                                {{ $court->type }} • 
                                <span class="font-weight-bold">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="carousel-item h-100 active" style="background-color: #5e72e4;">
                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                            <h5 class="text-white mb-1">Belum ada Lapangan</h5>
                            <p>Silakan tambah data lapangan di menu Data Lapangan.</p>
                        </div>
                    </div>
                @endforelse
              </div>
              <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- ROW 3: TABEL BOOKING TERBARU & PIE CHART (SAYA TAMBAHKAN TABEL DISINI) --}}
      <div class="row mt-4">
        
        {{-- ADDED: TABEL 5 BOOKING TERAKHIR --}}
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Booking Terbaru</h6>
                        <a href="{{ route('admin.bookings.index') }}" class="text-xs font-weight-bold text-primary">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lapangan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Pastikan kirim $recentBookings dari controller (limit 5) --}}
                            @forelse($recentBookings ?? [] as $booking)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $booking->user->name ?? 'User' }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-xs font-weight-bold">{{ $booking->court->name ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($booking->status == 'pending')
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    @elseif($booking->status == 'approved')
                                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                                    @elseif($booking->status == 'rejected')
                                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">{{ $booking->status }}</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        {{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <span class="text-xs text-secondary">Belum ada booking masuk.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- PIE CHART (UKURANNYA SAYA SESUAIKAN JADI col-lg-5) --}}
        <div class="col-lg-5">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Lapangan Terpopuler</h6>
                    <p class="text-sm mb-0">Berdasarkan total booking</p>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-pie" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
      </div>
      
      <footer class="footer pt-3">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>document.write(new Date().getFullYear())</script>,
                made with <i class="fa fa-heart"></i> by Tim Booking.
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <script>
        // --- 1. CONFIG LINE CHART (PENDAPATAN) ---
        var ctx1 = document.getElementById("chart-income").getContext("2d");
        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                datasets: [{
                    label: "Pendapatan (Rp)",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: @json($chartIncome ?? []),
                    maxBarThickness: 6
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y: { grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [5, 5] }, ticks: { display: true, padding: 10, color: '#fbfbfb', font: { size: 11, family: "Open Sans", style: 'normal', lineHeight: 2 } } },
                    x: { grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false, borderDash: [5, 5] }, ticks: { display: true, color: '#ccc', padding: 20, font: { size: 11, family: "Open Sans", style: 'normal', lineHeight: 2 } } },
                },
            },
        });

        // --- 2. CONFIG PIE CHART (POPULARITAS) ---
        var ctx2 = document.getElementById("chart-pie").getContext("2d");
        new Chart(ctx2, {
            type: "doughnut",
            data: {
                labels: @json($pieLabels ?? []),
                datasets: [{
                    label: "Jumlah Booking",
                    weight: 9,
                    cutout: 60,
                    tension: 0.9,
                    pointRadius: 2,
                    borderWidth: 2,
                    backgroundColor: ['#5e72e4', '#2dce89', '#f5365c', '#fb6340', '#11cdef'],
                    data: @json($pieValues ?? []),
                    fill: false
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'right' } },
                interaction: { intersect: false, mode: 'index' },
            },
        });
    </script>
    @endpush
@endsection