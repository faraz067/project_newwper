@extends('layouts.app') {{-- Sesuaikan dengan nama layout admin kamu (misal: layouts.admin) --}}

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-bold">Edit Booking #{{ $booking->id }}</h5>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
                            &larr; Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Form Update --}}
                    {{-- Perhatikan Route ini menggunakan admin.bookings.update --}}
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- KOLOM KIRI: Informasi User & Lapangan --}}
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder opacity-7 mb-3">Detail Penyewa</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nama Penyewa</label>
                                    <input type="text" class="form-control" value="{{ $booking->user->name ?? 'User Terhapus' }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" value="{{ $booking->user->email ?? '-' }}" disabled>
                                </div>

                                <hr class="horizontal dark my-3">

                                <h6 class="text-uppercase text-body text-xs font-weight-bolder opacity-7 mb-3">Detail Lapangan</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Lapangan</label>
                                    <input type="text" class="form-control" value="{{ $booking->court->name ?? 'Lapangan Terhapus' }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Main</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y') }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jam</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}" disabled>
                                        <span class="align-self-center">-</span>
                                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}" disabled>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Total Harga</label>
                                    <input type="text" class="form-control fw-bold" value="Rp {{ number_format($booking->total_price) }}" disabled>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Status & Bukti Bayar --}}
                            <div class="col-md-6 border-start">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder opacity-7 mb-3">Status & Pembayaran</h6>

                                {{-- STATUS (Bisa Diedit) --}}
                                <div class="mb-4">
                                    <label for="status" class="form-label fw-bold text-primary">Status Booking</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                        <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                                        <option value="rejected" {{ $booking->status == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Ubah status secara manual jika diperlukan.</small>
                                </div>

                                {{-- BUKTI TRANSFER --}}
                                <div class="mb-3">
                                    <label class="form-label">Bukti Transfer</label>
                                    <div class="border rounded p-2 text-center bg-light">
                                        @if($booking->payment_proof)
                                            <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 300px;">
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    Lihat Ukuran Penuh
                                                </a>
                                            </div>
                                        @else
                                            <div class="py-5 text-muted">
                                                <i class="fas fa-image fa-2x mb-2"></i>
                                                <p class="mb-0">User belum upload bukti transfer.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr class="horizontal dark">

                        {{-- TOMBOL SUBMIT --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection