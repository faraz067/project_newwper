<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Premium Dashboard - GOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #2ec4b6;
            --danger-color: #e71d36;
            --bg-body: #f8f9fe;
        }
        body { background-color: var(--bg-body); font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        
        .navbar { background: white !important; border-bottom: 1px solid #e9ecef; }
        .navbar-brand { color: var(--primary-color) !important; font-size: 1.5rem; }

        .card { border: none; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); overflow: hidden; }
        .card-stats { background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); color: white; }
        
        .hover-lift { transition: all 0.3s ease; }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }

        .status-pill { padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
        .bg-pending { background: #ff9f1c; color: white; }
        .bg-confirmed { background: #4361ee; color: white; }
        .bg-completed { background: #2ec4b6; color: white; }
        .bg-rejected { background: #e71d36; color: white; }
        
        .search-wrapper { background: white; border-radius: 50px; padding: 5px 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .search-wrapper input { border: none; outline: none; box-shadow: none !important; }

        .btn-action { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: 0.2s; border: none; }
        .empty-state img { max-width: 200px; filter: grayscale(1); opacity: 0.5; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="fas fa-bolt-lightning me-2"></i>Staff<span class="text-dark">Admin</span>
        </a>
        <div class="ms-auto d-flex align-items-center">
            <div class="text-end me-3 d-none d-sm-block">
                <small class="text-muted d-block small">Petugas Aktif</small>
                <span class="fw-bold text-dark">Staff Operasional</span>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-light rounded-circle shadow-sm" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-power-off text-danger"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card card-stats p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1 small">JADWAL HARI INI</h6>
                        <h2 class="fw-bold mb-0 text-white">{{ count($todaysBookings) }}</h2>
                    </div>
                    <div class="fs-1 opacity-25"><i class="fas fa-calendar-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">TOTAL SELESAI</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ $allBookings->where('status', 'completed')->count() }}</h2>
                    </div>
                    <div class="fs-1 text-success opacity-25"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 small">PENDAPATAN EKSTRA</h6>
                        <h2 class="fw-bold mb-0 text-primary">Rp {{ number_format($allBookings->sum('extra_charge'), 0, ',', '.') }}</h2>
                    </div>
                    <div class="fs-1 text-primary opacity-25"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-play text-primary me-2"></i>Sesi Berjalan</h5>
                <span class="badge bg-white text-primary border rounded-pill px-3 py-2 shadow-sm">{{ date('l, d M Y') }}</span>
            </div>
            
            <div class="row">
                @forelse($todaysBookings as $booking)
                <div class="col-md-6 mb-4">
                    <div class="card hover-lift h-100 border-top border-4 border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="status-pill bg-{{ $booking->status }}">
                                    {{ $booking->status }}
                                </span>
                                <span class="text-muted small fw-bold"><i class="far fa-clock me-1"></i>{{ $booking->start_time->format('H:i') }}</span>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-1">{{ $booking->court->name }}</h5>
                            <p class="text-muted small mb-3"><i class="fas fa-user-circle me-1"></i> {{ $booking->user->name }}</p>

                            <div class="p-3 rounded-3 bg-light mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-muted">Durasi:</span>
                                    <span class="small fw-bold">1 Sesi</span>
                                </div>
                                @if($booking->extra_charge > 0)
                                <div class="d-flex justify-content-between align-items-center border-top pt-1 mt-1">
                                    <span class="small text-danger fw-bold">Tambahan:</span>
                                    <span class="small text-danger fw-bold">+ Rp {{ number_format($booking->extra_charge, 0, ',', '.') }}</span>
                                </div>
                                <div class="x-small text-muted fst-italic mt-1 border-top pt-1">{{ $booking->note ?? 'Tanpa catatan' }}</div>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                @if($booking->status == 'pending')
                                    <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST" class="d-flex w-100 gap-1">
                                        @csrf
                                        <button name="status" value="confirmed" class="btn btn-primary btn-sm flex-grow-1 rounded-pill">Terima</button>
                                        <button name="status" value="rejected" class="btn btn-outline-danger btn-sm flex-grow-1 rounded-pill">Tolak</button>
                                    </form>
                                @elseif($booking->status == 'confirmed')
                                    <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button name="status" value="completed" class="btn btn-success btn-sm w-100 rounded-pill py-2">
                                            Selesaikan Sesi
                                        </button>
                                    </form>
                                    <button class="btn btn-dark btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#chargeModal{{ $booking->id }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm w-100 rounded-pill disabled">Sesi Selesai</button>
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#chargeModal{{ $booking->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="chargeModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <form action="{{ route('staff.booking.charge', $booking->id) }}" method="POST">
                                @csrf
                                <div class="modal-header border-0 bg-light">
                                    <h5 class="modal-title fw-bold">Input Biaya Tambahan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">NOMINAL BIAYA (RP)</label>
                                        <input type="number" name="extra_charge" class="form-control form-control-lg bg-light border-0" value="{{ $booking->extra_charge }}" placeholder="Contoh: 50000" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small fw-bold text-muted">ALASAN / CATATAN</label>
                                        <textarea name="note" class="form-control bg-light border-0" rows="3" placeholder="Contoh: Denda keterlambatan 15 menit">{{ $booking->note }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">Simpan Biaya</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 bg-white rounded-4 mb-4 shadow-sm">
                    <div class="empty-state">
                        <img src="https://illustrations.popsy.co/gray/work-from-home.svg" alt="Empty">
                        <p class="mt-3 text-muted">Belum ada aktivitas untuk sesi ini.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="search-wrapper d-flex align-items-center mb-4">
                <i class="fas fa-search text-muted"></i>
                <form action="{{ route('staff.dashboard') }}" method="GET" class="w-100">
                    <input type="text" name="search" class="form-control border-0" placeholder="Cari penyewa..." value="{{ request('search') }}">
                </form>
            </div>

            <div class="card p-3 shadow-sm">
                <h6 class="fw-bold text-dark mb-4 px-2">Log Aktivitas Terbaru</h6>
                @forelse($allBookings as $booking)
                <div class="d-flex align-items-center p-2 mb-3 border-bottom border-light hover-lift rounded-3">
                    <div class="flex-shrink-0 bg-light rounded-3 p-2 text-center" style="min-width: 55px;">
                        <span class="d-block fw-bold text-primary small">{{ $booking->start_time->format('H:i') }}</span>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h6 class="mb-0 text-dark small fw-bold text-truncate" style="max-width: 140px;">{{ $booking->user->name }}</h6>
                        <span class="text-muted x-small d-block">{{ $booking->court->name }}</span>
                    </div>
                    <div>
                        @if($booking->status == 'pending')
                            <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST">
                                @csrf
                                <button name="status" value="confirmed" class="btn btn-primary btn-action shadow-sm"><i class="fas fa-check"></i></button>
                            </form>
                        @else
                            <i class="fas fa-circle-check fs-5 text-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'rejected' ? 'danger' : 'primary') }}"></i>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center text-muted small py-4">Tidak ada data.</p>
                @endforelse
                
                <div class="mt-3 px-2">
                    {{ $allBookings->appends(['search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-5 text-muted">
    <small>GOR System &bull; Version 2.0 &bull; 2026</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>