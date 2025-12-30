<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/"><i class="fas fa-running me-2"></i>GOR Booking</a>
        <div class="d-flex">
            <a href="{{ route('booking.history') }}" class="btn btn-outline-light btn-sm me-2">Riwayat Saya</a>
            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        
        <div class="col-md-5 order-md-2 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>Cek Jadwal Terisi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('booking.create') }}" method="GET" class="mb-3">
                        <label class="small text-muted mb-1">Lihat ketersediaan tanggal:</label>
                        <div class="input-group">
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                            <button class="btn btn-dark" type="submit">Cek</button>
                        </div>
                    </form>

                    <hr>

                    @if($existingBookings->isEmpty())
                        <div class="text-center py-4 text-success">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <p class="fw-bold mb-0">Semua Kosong!</p>
                            <small>Silakan pilih jam main sesukamu.</small>
                        </div>
                    @else
                        <h6 class="fw-bold text-danger mb-3">⛔ Slot berikut SUDAH DIBOOKING:</h6>
                        <div class="list-group list-group-flush">
                            @foreach($existingBookings as $booked)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="fw-bold text-dark">
                                            <i class="fas fa-map-marker-alt text-primary me-1"></i> {{ $booked->court->name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $booked->user->name }}
                                        </small>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">
                                        {{ $booked->start_time->format('H:i') }} - {{ $booked->end_time->format('H:i') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-7 order-md-1">
            <div class="card shadow p-4 border-0">
                <h3 class="fw-bold text-secondary mb-4"><i class="fas fa-edit me-2"></i>Isi Form Booking</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Lapangan</label>
                        <select name="court_id" class="form-select" required>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}">
                                    {{ $court->name }} (Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} / jam)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Main</label>
                        <input type="date" name="date" class="form-control" value="{{ $date }}" required> 
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jam Selesai</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-1"></i> 
                        Pastikan jam yang kamu pilih tidak bertabrakan dengan jadwal di sebelah kanan (atau di atas pada tampilan HP).
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                        <i class="fas fa-save me-2"></i>BOOKING SEKARANG
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

</body>
</html>