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
                        <h6>Data Pengguna</h6>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mb-0">
                            <i class="fas fa-plus me-2"></i> Tambah User
                        </a>
                    </div>

                    {{-- === FORM PENCARIAN & FILTER (MODIFIKASI BARU) === --}}
                    <form action="{{ route('admin.users.index') }}" method="GET" class="mt-4">
                        <div class="row g-2 align-items-center">
                            
                            {{-- 1. SEARCH INPUT (Col-md-6) --}}
                            <div class="col-md-6">
                                <div class="input-group input-group-outline is-filled">
                                    {{-- Background Putih & Tinggi 42px --}}
                                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / Email..." value="{{ request('search') }}" style="background-color: #fff !important; height: 42px;">
                                </div>
                            </div>

                            {{-- 2. FILTER ROLE (Col-md-3) --}}
                            <div class="col-md-3">
                                <div class="input-group input-group-outline is-filled">
                                    <select name="role" class="form-control" style="background-color: #fff !important; height: 42px; cursor: pointer;">
                                        <option value="">-- Semua Role --</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>

                            {{-- 3. TOMBOL ACTION (Col-md-3) --}}
                            <div class="col-md-3 d-flex gap-1">
                                <button type="submit" class="btn btn-primary w-100 mb-0" style="height: 42px;">
                                    CARI
                                </button>

                                {{-- Tombol Reset muncul jika ada search atau filter --}}
                                @if(request('search') || request('role'))
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger w-100 mb-0 d-flex align-items-center justify-content-center" style="height: 42px;">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User Info</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bergabung</th>
                                    <th class="text-secondary opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    {{-- KOLOM 1: INFO USER --}}
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="avatar avatar-sm me-3 bg-gradient-primary rounded-circle">
                                                <span class="text-white text-xs">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- KOLOM 2: ROLE --}}
                                    <td>
                                        @if($user->hasRole('admin'))
                                            <span class="badge badge-sm bg-gradient-danger">Admin</span>
                                        @elseif($user->hasRole('staff'))
                                            <span class="badge badge-sm bg-gradient-warning">Staff</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-success">User</span>
                                        @endif
                                    </td>

                                    {{-- KOLOM 3: TANGGAL --}}
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    {{-- KOLOM 4: AKSI --}}
                                    <td class="align-middle">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-link text-dark px-3 mb-0">
                                            <i class="fas fa-pencil-alt text-dark me-2"></i>Edit
                                        </a>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger px-3 mb-0">
                                                <i class="far fa-trash-alt me-2"></i>Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                {{-- TAMPILAN JIKA DATA KOSONG --}}
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="ni ni-single-02 text-secondary text-lg mb-2 opacity-5"></i>
                                            <h6 class="text-secondary mb-0">User tidak ditemukan.</h6>
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
                        Menampilkan {{ $users->firstItem() }} s/d {{ $users->lastItem() }} dari {{ $users->total() }} user
                    </div>
                    <div class="mt-3">
                        {{-- withQueryString() memastikan filter tidak hilang saat pindah halaman --}}
                        {{ $users->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection