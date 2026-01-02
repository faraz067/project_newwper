@extends('layouts.app')

@section('title', 'Booking Lapangan')

@push('styles')
<style>
    .price-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        color: white;
    }
    /* Style tambahan untuk opsi disabled */
    option:disabled {
        background-color: #f8d7da; /* Latar merah muda */
        color: #842029; /* Teks merah tua */
        font-weight: bold;
    }
</style>
@endpush

@section('content')

<div class="container pb-5">
    <div class="row">

        {{-- SIDEBAR CEK JADWAL --}}
        <div class="col-md-5 order-md-2 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Cek Ketersediaan
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('booking.create') }}" method="GET" class="mb-3">
                        <label class="small fw-bold text-muted">Pilih Tanggal</label>
                        <div class="input-group">
                            <input type="date" name="date" class="form-control" value="{{ $date ?? date('Y-m-d') }}">
                            <button class="btn btn-dark">Cek</button>
                        </div>
                    </form>

                    <hr>

                    <h6 class="small fw-bold text-uppercase text-muted mb-3">
                        Status Lapangan ({{ \Carbon\Carbon::parse($date ?? now())->format('d M Y') }})
                    </h6>

                    @if(isset($existingBookings) && $existingBookings->isEmpty())
                        <div class="text-center text-success py-4">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <p class="fw-bold mb-0">Semua Lapangan Kosong</p>
                        </div>
                    @elseif(isset($existingBookings))
                        @foreach($existingBookings as $booked)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong>{{ $booked->court->name }}</strong><br>
                                    <small>{{ $booked->user->name }}</small>
                                </div>
                                <span class="badge bg-danger">
                                    {{ $booked->start_time->format('H:i') }} - {{ $booked->end_time->format('H:i') }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- FORM BOOKING --}}
        <div class="col-md-7 order-md-1">
            <div class="card shadow border-0">
                <div class="card-body p-4">

                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-edit me-2"></i>Form Booking Baru
                    </h3>

                    <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                        @csrf
                        
                        {{-- Input Hidden Tanggal (Penting!) --}}
                        <input type="hidden" name="date" value="{{ $date ?? date('Y-m-d') }}">

                        {{-- LAPANGAN (MODIFIKASI DI SINI) --}}
                        <div class="mb-4">
                            <label class="fw-bold small text-uppercase">Pilih Lapangan</label>
                            
                            <select name="court_id" id="courtSelect" class="form-select form-select-lg" required onchange="calculatePrice()">
                                <option value="" data-price="0" selected disabled>-- Pilih Lapangan --</option>
                                
                                @foreach($courts as $court)
                                    @php
                                        // Cek apakah nama lapangan mengandung kata "Badminton"
                                        $isRenovation = stripos($court->name, 'Badminton') !== false;
                                    @endphp

                                    @if($isRenovation)
                                        {{-- OPSI MATI (DISABLED) --}}
                                        <option value="{{ $court->id }}" disabled>
                                            🚧 {{ $court->name }} (SEDANG RENOVASI - TIDAK BISA DIPILIH)
                                        </option>
                                    @else
                                        {{-- OPSI NORMAL --}}
                                        {{-- Pastikan data-price menggunakan price_per_hour sesuai kodemu --}}
                                        <option value="{{ $court->id }}" data-price="{{ $court->price_per_hour }}">
                                            {{ $court->name }} (Rp {{ number_format($court->price_per_hour) }}/jam)
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            {{-- Pesan Peringatan Kecil --}}
                            @if($courts->contains(fn($c) => stripos($c->name, 'Badminton') !== false))
                                <div class="alert alert-warning mt-2 d-flex align-items-center p-2" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <small>Mohon maaf, <strong>Lapangan Badminton</strong> sedang dalam perbaikan.</small>
                                </div>
                            @endif
                        </div>

                        {{-- JAM --}}
                        <div class="row mb-4">
                            <div class="col">
                                <label class="small">Jam Mulai</label>
                                <input type="time" name="start_time" id="startTime" class="form-control" required onchange="calculatePrice()">
                            </div>
                            <div class="col">
                                <label class="small">Jam Selesai</label>
                                <input type="time" name="end_time" id="endTime" class="form-control" required onchange="calculatePrice()">
                            </div>
                        </div>

                        {{-- TOTAL --}}
                        <div class="card price-card mb-4">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <small>Total Bayar</small>
                                    <h2 id="totalPriceDisplay">Rp 0</h2>
                                </div>
                                <div class="text-end">
                                    <small>Durasi: <span id="durationDisplay">0</span> Jam</small><br>
                                    <small>Harga/Jam: <span id="priceDisplay">Rp 0</span></small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            LANJUT KE PEMBAYARAN
                        </button>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT HITUNG HARGA OTOMATIS --}}
<script>
    function calculatePrice() {
        // 1. Ambil elemen
        const courtSelect = document.getElementById('courtSelect');
        const startTimeInput = document.getElementById('startTime').value;
        const endTimeInput = document.getElementById('endTime').value;

        // 2. Ambil Harga dari atribut data-price (Pastikan ada)
        let pricePerHour = 0;
        if(courtSelect.selectedIndex > 0) {
            // Cek apakah opsi yang dipilih valid (tidak disabled)
            const selectedOption = courtSelect.options[courtSelect.selectedIndex];
            if (!selectedOption.disabled) {
                pricePerHour = selectedOption.getAttribute('data-price');
            }
        }

        // 3. Hitung Durasi
        let duration = 0;
        if (startTimeInput && endTimeInput) {
            const start = new Date("2000-01-01 " + startTimeInput);
            const end = new Date("2000-01-01 " + endTimeInput);
            
            // Hitung selisih dalam jam
            const diff = (end - start) / 1000 / 60 / 60;
            
            if (diff > 0) {
                duration = diff;
            }
        }

        // 4. Hitung Total
        const total = duration * pricePerHour;

        // 5. Tampilkan ke Layar
        document.getElementById('durationDisplay').innerText = duration;
        document.getElementById('priceDisplay').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(pricePerHour);
        document.getElementById('totalPriceDisplay').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
    }
</script>

@endsection