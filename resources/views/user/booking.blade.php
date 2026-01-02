@extends('layouts.app')

@section('title', 'Booking Lapangan')

@push('styles')
<style>
    .price-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        color: white;
    }
    /* Style tambahan untuk opsi disabled (Fitur Teman) */
    option:disabled {
        background-color: #f8d7da; /* Latar merah muda */
        color: #842029; /* Teks merah tua */
        font-weight: bold;
    }
</style>
@endpush

@section('content')

<div class="container mt-3">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

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
                        
                        {{-- Input Hidden Tanggal (Pakai Versi Teman yang lebih aman) --}}
                        <input type="hidden" name="date" value="{{ $date ?? date('Y-m-d') }}">

                        {{-- LAPANGAN --}}
                        <div class="mb-4">
                            <label class="fw-bold small text-uppercase">Pilih Lapangan</label>
                            
                            {{-- Hapus onchange="calculatePrice()" karena kita pakai EventListener di script bawah --}}
                            <select name="court_id" id="courtSelect" class="form-select form-select-lg" required>
                                <option value="" data-price="0" selected disabled>-- Pilih Lapangan --</option>
                                
                                @foreach($courts as $court)
                                    @php
                                        // LOGIC TEMAN: Cek apakah sedang renovasi
                                        $isRenovation = stripos($court->name, 'Badminton') !== false;
                                    @endphp

                                    @if($isRenovation)
                                        {{-- Opsi Mati --}}
                                        <option value="{{ $court->id }}" disabled>
                                            🚧 {{ $court->name }} (SEDANG RENOVASI)
                                        </option>
                                    @else
                                        {{-- Opsi Normal --}}
                                        <option value="{{ $court->id }}" data-price="{{ $court->price_per_hour }}">
                                            {{ $court->name }} (Rp {{ number_format($court->price_per_hour) }}/jam)
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            {{-- Pesan Peringatan Kecil (Fitur Teman) --}}
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
                                <input type="time" name="start_time" id="startTime" class="form-control" required>
                            </div>
                            <div class="col">
                                <label class="small">Jam Selesai</label>
                                <input type="time" name="end_time" id="endTime" class="form-control" required>
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

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // KITA PAKAI LOGIC JAVASCRIPT KAMU (KARENA LEBIH RAPI)
        // TAPI DITAMBAH PENGECEKAN DISABLED DARI TEMAN
        
        const courtSelect = document.getElementById('courtSelect');
        const startTimeInput = document.getElementById('startTime');
        const endTimeInput = document.getElementById('endTime');

        const totalPriceDisplay = document.getElementById('totalPriceDisplay');
        const durationDisplay = document.getElementById('durationDisplay');
        const priceDisplay = document.getElementById('priceDisplay');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
        }

        function calculateTotal() {
            // Ambil opsi yang dipilih
            const selectedOption = courtSelect.options[courtSelect.selectedIndex];
            
            // CEK: Apakah opsi disabled? (Jaga-jaga kalau user inspect element)
            if (selectedOption.disabled) {
                alert("Lapangan ini sedang renovasi!");
                courtSelect.value = ""; // Reset
                return;
            }

            const pricePerHour = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const startVal = startTimeInput.value;
            const endVal = endTimeInput.value;

            // Update tampilan harga per jam
            priceDisplay.textContent = formatRupiah(pricePerHour);

            if (startVal && endVal && pricePerHour > 0) {
                const start = new Date(`1970-01-01T${startVal}:00`);
                const end = new Date(`1970-01-01T${endVal}:00`);

                let diff = end - start;

                if (diff <= 0) {
                    totalPriceDisplay.textContent = "Jam Invalid";
                    durationDisplay.textContent = "0";
                    return;
                }

                const durationInHours = diff / (1000 * 60 * 60);
                const totalCost = durationInHours * pricePerHour;

                durationDisplay.textContent = durationInHours.toFixed(1);
                totalPriceDisplay.textContent = formatRupiah(totalCost);
            } else {
                totalPriceDisplay.textContent = "Rp 0";
                durationDisplay.textContent = "0";
            }
        }

        // Pasang Event Listener (Lebih bersih daripada onchange di HTML)
        courtSelect.addEventListener('change', calculateTotal);
        startTimeInput.addEventListener('change', calculateTotal);
        endTimeInput.addEventListener('change', calculateTotal);
    });
</script>
@endpush