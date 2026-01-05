@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success text-white" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-white" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                
                {{-- === HEADER & FILTER === --}}
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Daftar Booking Masuk</h6>
                    </div>

                    {{-- FORM FILTER (VERSI SAFE MODE) --}}
                    <form action="{{ route('admin.bookings.index') }}" method="GET" class="mt-4">
                        <div class="row g-2 align-items-center">
                            
                            {{-- 1. SEARCH INPUT (Pakai Placeholder, Hapus Label Mengambang) --}}
                            <div class="col-md-4">
                                <div class="input-group input-group-outline is-filled">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / Email..." value="{{ request('search') }}" style="background-color: #fff !important; height: 42px;">
                                </div>
                            </div>

                            {{-- 2. DROPDOWN STATUS --}}
                            <div class="col-md-3">
                                <div class="input-group input-group-outline is-filled">
                                    <select name="status" class="form-control" onchange="this.form.submit()" style="background-color: #fff !important; height: 42px;">
                                        <option value="">-- Semua Status --</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            {{-- 3. FILTER TANGGAL --}}
                            <div class="col-md-3">
                                <div class="input-group input-group-outline is-filled">
                                    <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()" style="background-color: #fff !important; height: 42px;">
                                </div>
                            </div>

                            {{-- 4. TOMBOL ACTION (Ganti Icon dengan TEKS) --}}
                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary w-100 mb-0" style="height: 42px;">
                                    CARI
                                </button>

                                @if(request('search') || request('status') || request('date'))
                                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-danger w-100 mb-0 d-flex align-items-center justify-content-center" style="height: 42px;">
                                        RESET
                                    </a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
                {{-- === END HEADER === --}}

                <div class="card-body px-0 pt-0 pb-2 mt-3">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penyewa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lapangan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jadwal Main</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total & Bukti</th>
                                    <th class="text-secondary opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    {{-- KOLOM 1: PENYEWA --}}
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3 bg-gradient-primary rounded-circle">
                                                    <span class="text-white text-xs">{{ substr($booking->user->name ?? 'U', 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $booking->user->name ?? 'User Terhapus' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $booking->user->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- KOLOM 2: LAPANGAN --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $booking->court->name ?? 'Lapangan Dihapus' }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $booking->court->type ?? '' }}</p>
                                    </td>

                                    {{-- KOLOM 3: JADWAL --}}
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('d F Y') }}
                                        </span>
                                        <br>
                                        <span class="text-secondary text-xs">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                        </span>
                                    </td>

                                    {{-- KOLOM 4: STATUS --}}
                                    <td class="align-middle text-center text-sm">
                                        @if($booking->status == 'pending')
                                            <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge badge-sm bg-gradient-primary">Confirmed</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge badge-sm bg-gradient-success">Completed</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge badge-sm bg-gradient-danger">Cancelled</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>

                                    {{-- KOLOM 5: TOTAL & BUKTI --}}
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                        @if($booking->payment_proof)
                                            <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="btn btn-link text-info text-xs mb-0 px-0">
                                                <i class="fas fa-file-image me-1"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-secondary text-xs">Belum Upload</span>
                                        @endif
                                    </td>

                                    {{-- KOLOM 6: AKSI --}}
                                    <td class="align-middle">
                                        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                            <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="ni ni-calendar-grid-58 text-secondary text-lg mb-2 opacity-5"></i>
                                            <h6 class="text-secondary mb-0">Data tidak ditemukan.</h6>
                                            <p class="text-xs text-secondary">Coba ubah kata kunci pencarian atau filter status.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- FOOTER --}}
                <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                    <div class="text-secondary text-sm">
                        Menampilkan {{ $bookings->firstItem() }} s/d {{ $bookings->lastItem() }} dari {{ $bookings->total() }} data
                    </div>
                    <div class="mt-3">
                        {{ $bookings->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection