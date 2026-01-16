@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Tambah Lapangan Baru</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white border-0 py-2 px-3 mb-4 text-sm rounded">
                            Terdapat kesalahan pada inputan Anda. Silakan cek kembali.
                        </div>
                    @endif

                    <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-control-label">Nama Lapangan</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Lapangan A">
                            @error('name')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Jenis Lapangan (Type)</label>
                            <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option value="Futsal" {{ old('type') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                <option value="Badminton" {{ old('type') == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                <option value="Basket" {{ old('type') == 'Basket' ? 'selected' : '' }}>Basket</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Harga per Jam (Rp)</label>
                            <input class="form-control @error('price_per_hour') is-invalid @enderror" type="number" name="price_per_hour" value="{{ old('price_per_hour') }}" required>
                            @error('price_per_hour')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Foto Lapangan</label>
                            <input class="form-control @error('photo') is-invalid @enderror" 
                                   type="file" 
                                   name="photo" 
                                   accept=".jpg,.jpeg,.png" 
                                   required>
                            <small class="text-muted d-block mt-1">
                                Format: <strong>JPG, JPEG, atau PNG</strong>. Maksimal ukuran file: <strong>2MB</strong>.
                            </small>
                            @error('photo')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary btn-sm ms-auto mt-4">Simpan Lapangan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection