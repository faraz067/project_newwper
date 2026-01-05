@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0 font-weight-bold">Tambah User Baru</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm ms-auto">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Form mengarah ke route STORE --}}
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            {{-- Input Nama --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Nama Lengkap</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama user">
                                    @error('name')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Input Email --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email Address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com">
                                    @error('email')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Input Password --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Password</label>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Minimal 6 karakter">
                                    @error('password')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Input Role (Pilihan) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-control-label">Role / Jabatan</label>
                                    <select class="form-control @error('role') is-invalid @enderror" name="role">
                                        <option value="">-- Pilih Role --</option>
                                        {{-- Loop data role dari Controller --}}
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success btn-sm">Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection