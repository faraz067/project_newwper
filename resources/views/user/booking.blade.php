<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .price-card {
            background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/"><i class="fas fa-running me-2"></i>GOR Booking</a>
        <div class="d-flex align-items-center">
            <a href="{{ route('booking.history') }}" class="btn btn-outline-light btn-sm me-2">Riwayat Saya</a>
            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row">
        
        <div class="col-md-5 order-md-2 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>Cek Ketersediaan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('booking.create') }}" method="GET" class="mb-3">
                        <label class="small text-muted mb-1 fw-bold">Pilih Tanggal:</label>
                        <div class="input-group">
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                            <button class="btn btn-dark" type="submit">Cek</button>
                        </div>
                    </form>

                    <hr>
                    
                    <h6 class="small text-muted text-uppercase fw-bold mb-3">Status Lapangan ({{ \Carbon\Carbon::parse($date)->format('d M Y') }})</h6>

                    @if($existingBookings->isEmpty())
                        <div class="text-center py-5 text-success bg-light rounded border border-success border-opacity-25">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <p class="fw-bold mb-0">Semua Lapangan Kosong!</p>
                            <small>Silakan pilih jam main sesukamu.</small>
                        </div>
                    @else
                        <div class="alert alert-info small mb-2"><i class="fas fa-info-circle"></i> Jam di bawah ini sudah terisi.</div>
                        <div class="list-group list-group-flush">
                            @foreach($existingBookings as $booked)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-2 py-3 border-bottom">
                                    <div>
                                        <div class="fw-bold text-dark">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $booked->court->name }}
                                        </div>
                                        <small class="text-muted"><i class="fas fa-user me-1"></i> {{ $booked->user->name }}</small>
                                    </div>
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
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
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h3 class="fw-bold text-secondary mb-4"><i class="fas fa-edit me-2"></i>Form Booking Baru</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase text-secondary small">1. Pilih Lapangan</label>
                            <select name="court_id" id="courtSelect" class="form-select form-select-lg" required>
                                <option value="" data-price="0">-- Klik untuk memilih --</option>
                                @foreach($courts as $court)
                                    <option value="{{ $court->id }}" 
                                            data-price="{{ $court->price }}"
                                            {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                        {{ $court->name }} (Rp {{ number_format($court->price, 0, ',', '.') }} / jam)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase text-secondary small">2. Tanggal Main</label>
                            <input type="date" name="date" class="form-control bg-light fw-bold" value="{{ $date }}" readonly required> 
                            <div class="form-text text-end"><a href="#" onclick="alert('Gunakan menu di sebelah kanan untuk mengganti tanggal.')">Ingin ganti tanggal?</a></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-uppercase text-secondary small">3. Durasi Main</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="small text-muted">Jam Mulai</label>
                                    <input type="time" name="start_time" id="startTime" class="form-control" value="{{ old('start_time') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Jam Selesai</label>
                                    <input type="time" name="end_time" id="endTime" class="form-control" value="{{ old('end_time') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="card price-card mb-4 border-0">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-white-50 text-uppercase fw-bold">Estimasi Total Bayar</small>
                                    <h2 class="mb-0 fw-bold" id="totalPriceDisplay">Rp 0</h2>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-white-50">Durasi: <span id="durationDisplay">0</span> Jam</small>
                                    <small class="d-block text-white-50">Harga/Jam: <span id="priceDisplay">Rp 0</span></small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg py-3 fw-bold shadow-sm">
                                <i class="fas fa-check-circle me-2"></i>LANJUT KE PEMBAYARAN
                            </button>
                            <a href="{{ route('booking.history') }}" class="btn btn-light text-muted">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courtSelect = document.getElementById('courtSelect');
        const startTimeInput = document.getElementById('startTime');
        const endTimeInput = document.getElementById('endTime');
        
        const totalPriceDisplay = document.getElementById('totalPriceDisplay');
        const durationDisplay = document.getElementById('durationDisplay');
        const priceDisplay = document.getElementById('priceDisplay');

        function calculatePrice() {
            // Ambil harga dari opsi yang dipilih (data-price)
            const selectedOption = courtSelect.options[courtSelect.selectedIndex];
            const pricePerHeader = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            // Ambil waktu
            const startVal = startTimeInput.value;
            const endVal = endTimeInput.value;

            if (pricePerHeader > 0 && startVal && endVal) {
                // Konversi waktu ke objek Date (hanya jam/menit yang penting)
                const start = new Date(`1970-01-01T${startVal}:00`);
                const end = new Date(`1970-01-01T${endVal}:00`);

                // Hitung selisih dalam jam
                let diff = (end - start) / 1000 / 60 / 60; // milidetik -> jam

                if (diff > 0) {
                    const total = diff * pricePerHeader;
                    
                    // Update Tampilan
                    totalPriceDisplay.innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total);
                    durationDisplay.innerText = diff.toFixed(1); // 1 desimal (misal 1.5 jam)
                    priceDisplay.innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(pricePerHeader);
                } else {
                    // Jika jam selesai lebih awal dari mulai
                    totalPriceDisplay.innerText = "Jam tidak valid";
                    durationDisplay.innerText = "0";
                }
            } else {
                totalPriceDisplay.innerText = "Rp 0";
                durationDisplay.innerText = "0";
                priceDisplay.innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(pricePerHeader);
            }
        }

        // Jalankan fungsi setiap kali ada perubahan input
        courtSelect.addEventListener('change', calculatePrice);
        startTimeInput.addEventListener('change', calculatePrice);
        endTimeInput.addEventListener('change', calculatePrice);
    });
</script>

</body>
</html>