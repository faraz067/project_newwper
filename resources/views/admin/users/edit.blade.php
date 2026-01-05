@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0 font-weight-bold">Edit User: {{ $user->name }}</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm ms-auto">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Form Update Menggunakan Method PUT --}}
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- PENTING: Ubah method jadi PUT --}}
                        
                        <div class="row">
                            {{-- Input Nama --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Nama Lengkap</label>
                                    {{-- Value diambil dari $user->name --}}
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Input Email --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email Address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Input Password (Opsional) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Password Baru (Opsional)</label>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti password">
                                    <small class="form-text text-muted text-xs">Isi hanya jika ingin mereset password user ini.</small>
                                    @error('password')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Input Role --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-control-label">Role / Jabatan</label>
                                    <select class="form-control @error('role') is-invalid @enderror" name="role">
                                        @foreach($roles as $role)
                                            {{-- Cek apakah user punya role ini, jika ya, tambahkan 'selected' --}}
                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection