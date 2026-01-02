@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Alert Notifikasi (Muncul kalau sukses approve/reject) --}}
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
                {{-- HEADER: JUDUL & PENCARIAN --}}
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Daftar Booking Masuk</h6>
                    
                    {{-- FORM PENCARIAN --}}
                    <form action="{{ route('admin.bookings.index') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="search" placeholder="Cari nama/lapangan..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                {{-- TABEL DATA --}}
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penyewa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lapangan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jadwal Main</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total & Bukti</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    {{-- KOLOM 1: PENYEWA --}}
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                {{-- Avatar default jika user tidak punya foto --}}
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

                                    {{-- KOLOM 4: STATUS (BADGE WARNA-WARNI) --}}
                                    <td class="align-middle text-center text-sm">
                                        @if($booking->status == 'pending')
                                            <span class="badge badge-sm bg-gradient-warning">Menunggu Konfirmasi</span>
                                        @elseif($booking->status == 'approved' || $booking->status == 'paid')
                                            <span class="badge badge-sm bg-gradient-success">Disetujui / Lunas</span>
                                        @elseif($booking->status == 'rejected')
                                            <span class="badge badge-sm bg-gradient-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>

                                    {{-- KOLOM 5: BUKTI BAYAR & HARGA --}}
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

                                    {{-- KOLOM 6: AKSI (APPROVE/REJECT) --}}
                                    <td class="align-middle">
                                        @if($booking->status == 'pending')
                                            {{-- Tombol Setujui --}}
                                            <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm px-3 mb-0" title="Setujui Booking">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm px-3 mb-0" title="Tolak Booking" onclick="return confirm('Yakin ingin menolak booking ini?')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-secondary text-xs font-weight-bold">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="ni ni-calendar-grid-58 text-secondary text-lg mb-2 opacity-5"></i>
                                            <h6 class="text-secondary mb-0">Belum ada data booking.</h6>
                                            <p class="text-xs text-secondary">Data akan muncul saat user melakukan pemesanan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- FOOTER: PAGINATION --}}
                <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                    <div class="text-secondary text-sm">
                        Menampilkan {{ $bookings->firstItem() }} s/d {{ $bookings->lastItem() }} dari {{ $bookings->total() }} data
                    </div>
                    
                    <div class="mt-3 mt-lg-0">
                        {{-- Ini akan memunculkan tombol < 1 2 3 > otomatis --}}
                        {{ $bookings->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection