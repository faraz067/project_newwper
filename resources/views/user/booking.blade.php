@extends('layouts.app')

@section('title', 'Booking Lapangan')

@push('styles')
<style>
    .price-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        color: white;
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
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                            <button class="btn btn-dark">Cek</button>
                        </div>
                    </form>

                    <hr>

                    <h6 class="small fw-bold text-uppercase text-muted mb-3">
                        Status Lapangan ({{ \Carbon\Carbon::parse($date)->format('d M Y') }})
                    </h6>

                    @if($existingBookings->isEmpty())
                        <div class="text-center text-success py-4">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <p class="fw-bold mb-0">Semua Lapangan Kosong</p>
                        </div>
                    @else
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

                        <input type="hidden" name="date" value="{{ $date }}">

                        {{-- LAPANGAN --}}
                        <div class="mb-4">
                            <label class="fw-bold small text-uppercase">Pilih Lapangan</label>
                            <select name="court_id" id="courtSelect" class="form-select form-select-lg" required>
                                <option value="" data-price="0">-- Pilih --</option>
                                @foreach($courts as $court)
                                    <option value="{{ $court->id }}" data-price="{{ $court->price_per_hour }}">
                                        {{-- Ubah $court->price jadi $court->price_per_hour --}}
                                        {{ $court->name }} (Rp {{ number_format($court->price_per_hour) }}/jam)
                                    </option>
                                @endforeach
                            </select>
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

                        <button class="btn btn-success w-100 btn-lg">
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
        // 1. Ambil Element HTML
        const courtSelect = document.getElementById('courtSelect');
        const startTimeInput = document.getElementById('startTime');
        const endTimeInput = document.getElementById('endTime');

        const totalPriceDisplay = document.getElementById('totalPriceDisplay');
        const durationDisplay = document.getElementById('durationDisplay');
        const priceDisplay = document.getElementById('priceDisplay');

        // 2. Fungsi Format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
        }

        // 3. Fungsi Utama Perhitungan
        function calculateTotal() {
            // Ambil harga dari atribut data-price di <option>
            const selectedOption = courtSelect.options[courtSelect.selectedIndex];
            const pricePerHour = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            const startVal = startTimeInput.value; // Format "HH:mm"
            const endVal = endTimeInput.value;     // Format "HH:mm"

            // Update tampilan harga per jam
            priceDisplay.textContent = formatRupiah(pricePerHour);

            // Cek apakah jam mulai & selesai sudah diisi
            if (startVal && endVal && pricePerHour > 0) {
                // Konversi waktu string "14:30" ke object Date dummy
                const start = new Date(`1970-01-01T${startVal}:00`);
                const end = new Date(`1970-01-01T${endVal}:00`);

                // Hitung selisih waktu dalam milidetik
                let diff = end - start;

                // Jika user input jam selesai lebih kecil dari jam mulai (error logic)
                if (diff <= 0) {
                    totalPriceDisplay.textContent = "Jam Invalid";
                    durationDisplay.textContent = "0";
                    return;
                }

                // Konversi milidetik ke jam (decimal)
                // 1000 ms * 60 dtk * 60 mnt
                const durationInHours = diff / (1000 * 60 * 60);

                // Hitung Total
                const totalCost = durationInHours * pricePerHour;

                // Tampilkan ke layar
                durationDisplay.textContent = durationInHours.toFixed(1); // 1 desimal (contoh: 2.5 jam)
                totalPriceDisplay.textContent = formatRupiah(totalCost);
            } else {
                totalPriceDisplay.textContent = "Rp 0";
                durationDisplay.textContent = "0";
            }
        }

        // 4. Pasang Event Listener (Jalankan fungsi saat input berubah)
        courtSelect.addEventListener('change', calculateTotal);
        startTimeInput.addEventListener('change', calculateTotal);
        endTimeInput.addEventListener('change', calculateTotal);
    });
</script>
@endpush