<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/"><i class="fas fa-running me-2"></i>GOR Booking</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Halo, {{ Auth::user()->name }}</span>
            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-secondary">📜 Riwayat Booking</h3>
        <a href="{{ route('booking.create') }}" class="btn btn-success fw-bold shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Booking Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body p-0">
            @if($bookings->isEmpty())
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-3 opacity-50">
                    <p class="text-muted">Kamu belum pernah booking lapangan.</p>
                    <a href="{{ route('booking.create') }}" class="btn btn-primary btn-sm">Yuk Main!</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Lapangan</th>
                                <th>Jadwal Main</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th class="text-end pe-4" style="min-width: 250px;">Aksi / Upload Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $booking->court->name }}</div>
                                    <small class="text-muted">Dipesan: {{ $booking->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $booking->start_time->format('d M Y') }}</div>
                                    <span class="badge bg-light text-dark border">
                                        {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                                    </span>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i>Diterima</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-success"><i class="fas fa-flag-checkered me-1"></i>Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                
                                <td class="text-end pe-4">
                                    @if($booking->status == 'pending')
                                        
                                        @if($booking->payment_proof)
                                            <button class="btn btn-info btn-sm text-white disabled">
                                                <i class="fas fa-check-circle me-1"></i>Bukti Terkirim
                                            </button>
                                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                                Menunggu Verifikasi Admin
                                            </div>
                                        @else
                                            <form action="{{ route('booking.upload', $booking->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group input-group-sm justify-content-end">
                                                    <input type="file" name="payment_proof" class="form-control form-control-sm" style="max-width: 200px;" required>
                                                    <button class="btn btn-primary" type="submit" title="Kirim Bukti">
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                </div>
                                                <div class="small text-danger mt-1" style="font-size: 0.75rem;">
                                                    *Upload bukti transfer (JPG/PNG)
                                                </div>
                                            </form>
                                        @endif

                                    @elseif($booking->status == 'confirmed')
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-ticket-alt me-1"></i>Tiket Terbit
                                        </button>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>