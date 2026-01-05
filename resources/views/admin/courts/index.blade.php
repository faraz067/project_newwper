@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    
    {{-- === ALERT NOTIFIKASI === --}}
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
                
                {{-- === HEADER: JUDUL & TOMBOL TAMBAH === --}}
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Daftar Lapangan</h6>
                        <a href="{{ route('admin.courts.create') }}" class="btn btn-primary btn-sm mb-0">
                            <i class="fas fa-plus me-2"></i> Tambah Lapangan
                        </a>
                    </div>

                    {{-- === FORM PENCARIAN & FILTER === --}}
                    <form action="{{ route('admin.courts.index') }}" method="GET" class="mt-4">
                        <div class="row g-2 align-items-center">
                            
                            {{-- 1. SEARCH INPUT (Nama Lapangan) --}}
                            <div class="col-md-6">
                                <div class="input-group input-group-outline is-filled">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama lapangan..." value="{{ request('search') }}" style="background-color: #fff !important; height: 42px;">
                                </div>
                            </div>

                            {{-- 2. FILTER TIPE (Berdasarkan Jenis Olahraga) --}}
                            <div class="col-md-3">
                                <div class="input-group input-group-outline is-filled">
                                    <select name="type" class="form-control" style="background-color: #fff !important; height: 42px; cursor: pointer;">
                                        <option value="">-- Semua Jenis --</option>
                                        {{-- Sesuaikan value ini persis dengan yang ada di Database kolom 'type' --}}
                                        <option value="Futsal" {{ request('type') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                        <option value="Badminton" {{ request('type') == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                        <option value="Basket" {{ request('type') == 'Basket' ? 'selected' : '' }}>Basket</option>
                                        <option value="Mini Soccer" {{ request('type') == 'Mini Soccer' ? 'selected' : '' }}>Mini Soccer</option>
                                    </select>
                                </div>
                            </div>

                            {{-- 3. TOMBOL ACTION --}}
                            <div class="col-md-3 d-flex gap-1">
                                <button type="submit" class="btn btn-primary w-100 mb-0" style="height: 42px;">
                                    CARI
                                </button>

                                @if(request('search') || request('type'))
                                    <a href="{{ route('admin.courts.index') }}" class="btn btn-outline-danger w-100 mb-0 d-flex align-items-center justify-content-center" style="height: 42px;">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lapangan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga/Jam</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courts as $court)
                                <tr>
                                    {{-- KOLOM 1: GAMBAR & NAMA --}}
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div>
                                                {{-- Menampilkan Gambar --}}
                                                @if($court->photo)
                                                    <img src="{{ asset('storage/' . $court->photo) }}" class="avatar avatar-sm me-3 border-radius-lg" alt="court">
                                                @else
                                                    {{-- Gambar Default jika tidak ada foto --}}
                                                    <div class="avatar avatar-sm me-3 bg-gradient-info border-radius-lg d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-basketball-ball text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $court->name }}</h6>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- KOLOM 2: TIPE --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $court->type }}</p>
                                    </td>

                                    {{-- KOLOM 3: HARGA --}}
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 text-success">
                                            Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    {{-- KOLOM 4: STATUS --}}
                                    <td class="align-middle text-center text-sm">
                                        {{-- Logic Warna Badge Status --}}
                                        @php
                                            $badgeClass = 'bg-gradient-secondary'; // Default (Maintenance/dll)
                                            if(strtolower($court->status) == 'tersedia') {
                                                $badgeClass = 'bg-gradient-success';
                                            } elseif(strtolower($court->status) == 'penuh' || strtolower($court->status) == 'booked') {
                                                $badgeClass = 'bg-gradient-danger';
                                            }
                                        @endphp
                                        
                                        <span class="badge badge-sm {{ $badgeClass }}">
                                            {{ ucfirst($court->status) }}
                                        </span>
                                    </td>

                                    {{-- KOLOM 5: AKSI --}}
                                    <td class="align-middle">
                                        <a href="{{ route('admin.courts.edit', $court->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                            <i class="fas fa-pencil-alt text-dark me-2"></i>Edit
                                        </a>

                                        <form action="{{ route('admin.courts.destroy', $court->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger px-3 mb-0">
                                                <i class="far fa-trash-alt me-2"></i>Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                {{-- TAMPILAN JIKA KOSONG --}}
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="ni ni-building text-secondary text-lg mb-2 opacity-5"></i>
                                            <h6 class="text-secondary mb-0">Data lapangan tidak ditemukan.</h6>
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
                        Menampilkan {{ $courts->firstItem() }} s/d {{ $courts->lastItem() }} dari {{ $courts->total() }} lapangan
                    </div>
                    <div class="mt-3">
                        {{ $courts->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection