@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Edit Data Lapangan</h6>
                </div>
                <div class="card-body">
                    {{-- Alert jika ada error secara umum --}}
                    @if ($errors->any())
                        <div class="alert alert-danger text-white border-0 py-2 px-3 mb-4 text-sm rounded">
                            Terdapat kesalahan. Silakan periksa kembali inputan Anda.
                        </div>
                    @endif

                    <form action="{{ route('admin.courts.update', $court->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Nama Lapangan --}}
                        <div class="form-group mb-3">
                            <label class="form-control-label">Nama Lapangan</label>
                            <input class="form-control @error('name') is-invalid @enderror" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name', $court->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jenis Lapangan --}}
                        <div class="form-group mb-3">
                            <label class="form-control-label">Jenis Lapangan</label>
                            <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                                <option value="Futsal" {{ old('type', $court->type) == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                <option value="Badminton" {{ old('type', $court->type) == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                <option value="Basket" {{ old('type', $court->type) == 'Basket' ? 'selected' : '' }}>Basket</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga --}}
                        <div class="form-group mb-3">
                            <label class="form-control-label">Harga per Jam (Rp)</label>
                            <input class="form-control @error('price_per_hour') is-invalid @enderror" 
                                   type="number" 
                                   name="price_per_hour" 
                                   value="{{ old('price_per_hour', $court->price_per_hour) }}" 
                                   required>
                            @error('price_per_hour')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="form-group mb-3">
                            <label class="form-control-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                <option value="tersedia" {{ old('status', $court->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status', $court->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto Lapangan --}}
                        <div class="form-group mb-3">
                            <label class="form-control-label">Foto Lapangan</label>
                            <div class="mb-3">
                                @if($court->photo)
                                    <div class="d-block mb-2">
                                        <img src="{{ asset('storage/' . $court->photo) }}" alt="Foto Lama" class="img-thumbnail shadow-sm" style="max-height: 150px;">
                                    </div>
                                    <span class="badge bg-light text-dark text-xxs border mb-2">Foto saat ini</span>
                                @else
                                    <span class="text-muted text-xs d-block mb-2 italic">Belum ada foto terunggah</span>
                                @endif
                            </div>

                            <input class="form-control @error('photo') is-invalid @enderror" 
                                   type="file" 
                                   name="photo" 
                                   accept=".jpg,.jpeg,.png">
                            
                            {{-- Keterangan Teks --}}
                            <div class="mt-2">
                                <small class="text-muted d-block">
                                    Format: <strong>JPG, JPEG, atau PNG</strong>. Maksimal: <strong>2MB</strong>.
                                </small>
                                <small class="text-info d-block">
                                    *Biarkan kosong jika tidak ingin mengganti foto.
                                </small>
                            </div>

                            @error('photo')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.courts.index') }}" class="btn btn-light m-0">Batal</a>
                            <button type="submit" class="btn btn-primary m-0 ms-2">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection