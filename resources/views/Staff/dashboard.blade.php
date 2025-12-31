<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Staff - GOR Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .card-header { background-color: #212529; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-user-shield me-2"></i>Staff Panel</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Halo, Staff</span>
            <!-- Form logout tersembunyi -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <!-- Tombol logout -->
            <a href="{{ route('logout') }}"
            class="btn btn-outline-danger btn-sm"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">
            Periksa input denda/catatan Anda. Pastikan nominal berupa angka tanpa titik.
        </div>
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="fw-bold text-dark border-bottom pb-2">👋 Dashboard Operasional</h2>
        </div>

        <div class="col-12 mb-5">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Jadwal Main Hari Ini</h5>
                    <span class="badge bg-warning text-dark">{{ date('d M Y') }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Jam Main</th>
                                <th>Lapangan</th>
                                <th>Penyewa</th>
                                <th>Status</th>
                                <th>Biaya Tambahan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todaysBookings as $booking)
                            <tr>
                                <td class="align-middle fw-bold text-primary">
                                    {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                                </td>
                                <td class="align-middle">{{ $booking->court->name }}</td>
                                <td class="align-middle">{{ $booking->user->name }}</td>
                                <td class="align-middle">
                                    @if($booking->status == 'confirmed')
                                        <span class="badge bg-primary">Sedang Main / Siap</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $booking->status }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($booking->extra_charge > 0)
                                        <span class="text-danger fw-bold">+ Rp {{ number_format($booking->extra_charge, 0, ',', '.') }}</span>
                                        <br><small class="text-muted fst-italic">"{{ $booking->note }}"</small>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex gap-1">
                                        @if($booking->status == 'confirmed')
                                        <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST">
                                            @csrf
                                            <button name="status" value="completed" class="btn btn-success btn-sm" onclick="return confirm('Selesaikan sesi ini?')">
                                                ✅ Selesai
                                            </button>
                                        </form>
                                        @endif

                                        @if($booking->status == 'confirmed' || $booking->status == 'completed')
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#chargeModalToday{{ $booking->id }}">
                                                <i class="fas fa-coins"></i>
                                            </button>

                                            <div class="modal fade" id="chargeModalToday{{ $booking->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title">Biaya Tambahan ({{ $booking->user->name }})</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('staff.booking.charge', $booking->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body text-start">
                                                                <div class="mb-3">
                                                                    <label class="fw-bold">Nominal (Rp)</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">Rp</span>
                                                                        <input type="number" 
                                                                               name="extra_charge" 
                                                                               class="form-control form-control-lg" 
                                                                               value="{{ $booking->extra_charge }}" 
                                                                               placeholder="Contoh: 100000"
                                                                               oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                                    </div>
                                                                    <small class="text-danger fst-italic">*Angka saja, dilarang pakai titik.</small>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-bold">Catatan</label>
                                                                    <textarea name="note" class="form-control" placeholder="Contoh: Denda kerusakan / Beli air">{{ $booking->note }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary w-100">Simpan Biaya</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada jadwal main untuk hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Semua Riwayat Masuk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Penyewa</th>
                                    <th>Lapangan</th>
                                    <th>Status</th>
                                    <th>Aksi / Kelola</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allBookings as $booking)
                                <tr>
                                    <td>
                                        {{ $booking->start_time->format('d M Y, H:i') }}
                                        @if($booking->extra_charge > 0)
                                            <div class="text-danger small fw-bold mt-1">
                                                + Charge: Rp {{ number_format($booking->extra_charge, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->court->name }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                            <span class="badge bg-warning text-dark">Perlu Konfirmasi</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge bg-primary">Diterima</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($booking->status == 'pending')
                                                @if($booking->payment_proof)
                                                    <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="btn btn-info btn-sm text-white">
                                                        <i class="fas fa-image"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <button name="status" value="confirmed" class="btn btn-primary btn-sm"><i class="fas fa-check"></i></button>
                                                </form>
                                                <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <button name="status" value="rejected" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                                </form>

                                            @elseif($booking->status == 'confirmed' || $booking->status == 'completed')
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#chargeModalHistory{{ $booking->id }}">
                                                    <i class="fas fa-coins"></i> Input Biaya
                                                </button>
                                                
                                                <div class="modal fade" id="chargeModalHistory{{ $booking->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title">Biaya Tambahan (#{{ $booking->id }})</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('staff.booking.charge', $booking->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body text-start">
                                                                    <div class="mb-3">
                                                                        <label class="fw-bold">Nominal (Rp)</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text">Rp</span>
                                                                            <input type="number" 
                                                                                   name="extra_charge" 
                                                                                   class="form-control form-control-lg" 
                                                                                   value="{{ $booking->extra_charge }}" 
                                                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                                                        </div>
                                                                        <small class="text-danger">*Tanpa titik (Contoh: 100000)</small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="fw-bold">Catatan</label>
                                                                        <textarea name="note" class="form-control">{{ $booking->note }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-4 text-muted mt-5 border-top">
    <small>&copy; 2025 Sistem Booking GOR</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>