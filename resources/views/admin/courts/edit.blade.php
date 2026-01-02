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
                    {{-- Form mengarah ke route UPDATE --}}
                    <form action="{{ route('admin.courts.update', $court->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- WAJIB: HTML Form cuma support GET/POST, jadi kita 'tipu' pakai PUT --}}

                        <div class="form-group mb-3">
                            <label class="form-control-label">Nama Lapangan</label>
                            <input class="form-control" type="text" name="name" value="{{ old('name', $court->name) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-control-label">Jenis Lapangan</label>
                            <select class="form-control" name="type" required>
                                <option value="Futsal" {{ $court->type == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                <option value="Badminton" {{ $court->type == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                <option value="Basket" {{ $court->type == 'Basket' ? 'selected' : '' }}>Basket</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-control-label">Harga per Jam (Rp)</label>
                            <input class="form-control" type="number" name="price_per_hour" value="{{ old('price_per_hour', $court->price_per_hour) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-control-label">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="tersedia" {{ $court->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ $court->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-control-label">Foto Lapangan</label>
                            <div class="mb-2">
                                {{-- Tampilkan foto lama buat preview --}}
                                @if($court->photo)
                                    <img src="{{ asset('storage/' . $court->photo) }}" alt="Foto Lama" class="img-thumbnail" style="max-height: 150px;">
                                    <div class="text-muted text-xs mt-1">Foto saat ini</div>
                                @else
                                    <span class="text-muted text-xs">Belum ada foto</span>
                                @endif
                            </div>
                            <input class="form-control" type="file" name="photo">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
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