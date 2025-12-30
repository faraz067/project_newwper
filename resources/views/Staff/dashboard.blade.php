<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - GOR Booking</title>
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
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-user-shield me-2"></i>Admin Panel</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Halo, Staff</span>
            <a href="/logout" class="btn btn-outline-danger btn-sm">Logout</a>
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
                                        <span class="badge bg-primary">Siap Main</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $booking->status }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($booking->status == 'confirmed')
                                    <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST">
                                        @csrf
                                        <button name="status" value="completed" class="btn btn-success btn-sm" onclick="return confirm('Selesaikan sesi ini?')">
                                            ✅ Selesai Main
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Tidak ada jadwal main untuk hari ini.
                                </td>
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
                                    <th>Aksi Konfirmasi</th> </tr>
                            </thead>
                            <tbody>
                                @foreach($allBookings as $booking)
                                <tr>
                                    <td>{{ $booking->start_time->format('d M Y, H:i') }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->court->name }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge bg-primary">Diterima</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->status == 'pending')
                                            <div class="d-flex gap-2">
                                                
                                                @if($booking->payment_proof)
                                                    <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="btn btn-info btn-sm text-white" title="Lihat Bukti Bayar">
                                                        <i class="fas fa-image"></i> Bukti
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled title="User belum upload bukti">
                                                        Belum Bayar
                                                    </button>
                                                @endif

                                                <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <button name="status" value="confirmed" class="btn btn-primary btn-sm" onclick="return confirm('Terima booking ini?')">
                                                        <i class="fas fa-check"></i> Terima
                                                    </button>
                                                </form>

                                                <form action="{{ route('staff.booking.update', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <button name="status" value="rejected" class="btn btn-danger btn-sm" onclick="return confirm('Tolak booking ini?')">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted small">
                                                @if($booking->status == 'confirmed')
                                                    Sudah Disetujui
                                                @elseif($booking->status == 'rejected')
                                                    Ditolak
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        @endif
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

</body>
</html>