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
        <div class="d-flex align-items-center">
            <span class="navbar-text text-white me-3">Halo, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container pb-5">
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
                                <th class="text-end pe-4">Aksi / Pembayaran</th>
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
                                    Rp {{ number_format($booking->total_price + $booking->extra_charge, 0, ',', '.') }}
                                    @if($booking->extra_charge > 0)
                                        <div class="small text-danger" style="font-size: 0.7rem;">(+ Denda/Charge)</div>
                                    @endif
                                </td>

                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-primary"><i class="fas fa-check-circle me-1"></i>Lunas</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-success"><i class="fas fa-flag-checkered me-1"></i>Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $booking->id }}">
                                        <i class="fas fa-file-invoice-dollar me-1"></i> 
                                        {{ $booking->status == 'pending' ? 'Bayar / Detail' : 'Lihat Invoice' }}
                                    </button>

                                    <div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <div class="modal-header {{ $booking->status == 'confirmed' ? 'bg-success' : 'bg-primary' }} text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-receipt me-2"></i>Invoice #{{ $booking->id }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        @if($booking->status == 'pending')
                                                            <div class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill mb-2">⏳ Menunggu Pembayaran</div>
                                                            <p class="text-muted small">Silakan transfer sesuai nominal di bawah ini.</p>
                                                        @elseif($booking->status == 'confirmed')
                                                            <div class="badge bg-success px-3 py-2 fs-6 rounded-pill">✅ Pembayaran Diterima</div>
                                                        @endif
                                                    </div>

                                                    <div class="card bg-light border-0 mb-3">
                                                        <div class="card-body p-3">
                                                            <table class="table table-sm table-borderless mb-0">
                                                                <tr>
                                                                    <td class="text-muted">Lapangan</td>
                                                                    <td class="text-end fw-bold">{{ $booking->court->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Jadwal</td>
                                                                    <td class="text-end">{{ $booking->start_time->format('d M Y, H:i') }}</td>
                                                                </tr>
                                                                @if($booking->extra_charge > 0)
                                                                <tr>
                                                                    <td class="text-danger">Biaya Tambahan</td>
                                                                    <td class="text-end text-danger fw-bold">+ Rp {{ number_format($booking->extra_charge, 0, ',', '.') }}</td>
                                                                </tr>
                                                                <tr class="small text-muted fst-italic">
                                                                    <td colspan="2" class="text-end">"{{ $booking->note }}"</td>
                                                                </tr>
                                                                @endif
                                                                <tr class="border-top border-secondary mt-2">
                                                                    <td class="fw-bold text-primary pt-2 fs-5">TOTAL BAYAR</td>
                                                                    <td class="fw-bold text-primary text-end pt-2 fs-5">
                                                                        Rp {{ number_format($booking->total_price + $booking->extra_charge, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    @if($booking->status == 'pending')
                                                        <div class="alert alert-info border-info d-flex align-items-center mb-3" role="alert">
                                                            <i class="fas fa-university fa-2x me-3"></i>
                                                            <div>
                                                                <small class="text-uppercase text-muted fw-bold">Transfer Bank BCA</small>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span class="fs-5 fw-bold">123-456-7890</span>
                                                                    <button class="btn btn-sm btn-outline-secondary py-0" onclick="navigator.clipboard.writeText('1234567890'); alert('No Rekening Disalin!');">
                                                                        <i class="fas fa-copy"></i>
                                                                    </button>
                                                                </div>
                                                                <small class="text-muted">a.n. Pengelola GOR</small>
                                                            </div>
                                                        </div>

                                                        <form action="{{ route('booking.upload', $booking->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small">Upload Bukti Transfer</label>
                                                                <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                                                                <div class="form-text">Format: JPG, PNG. Maks 2MB.</div>
                                                            </div>
                                                            
                                                            @if($booking->payment_proof)
                                                                <div class="alert alert-warning py-2 small mb-2">
                                                                    <i class="fas fa-exclamation-circle"></i> Anda sudah pernah upload bukti. Upload lagi untuk mengganti.
                                                                </div>
                                                            @endif

                                                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                                                <i class="fas fa-paper-plane me-1"></i> Kirim Bukti Pembayaran
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(($booking->status == 'confirmed' || $booking->status == 'completed') && $booking->payment_proof)
                                                        <div class="text-center mt-2">
                                                            <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                                <i class="fas fa-image me-1"></i> Lihat Bukti Bayar Saya
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>