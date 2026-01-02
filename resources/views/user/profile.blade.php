@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white pb-0 border-0">
                    <h4 class="font-weight-bold mb-0">⚙️ Edit Profil</h4>
                </div>
                <div class="card-body">
                    
                    {{-- PESAN SUKSES --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- PESAN ERROR --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM EDIT PROFIL --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- BAGIAN FOTO PROFIL --}}
                        <div class="text-center mb-5">
                            <div class="position-relative d-inline-block">
                                {{-- Tampilan Foto --}}
                                {{-- Saya kasih ID="avatar-preview" untuk script JS di bawah --}}
                                @if(Auth::user()->avatar)
                                    <img id="avatar-preview" 
                                         src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                         class="rounded-circle shadow-sm" 
                                         width="150" height="150" 
                                         style="object-fit: cover; border: 4px solid #fff;">
                                @else
                                    {{-- Placeholder jika belum ada foto --}}
                                    <img id="avatar-preview" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" 
                                         class="rounded-circle shadow-sm" 
                                         width="150" height="150" 
                                         style="object-fit: cover; border: 4px solid #fff;">
                                @endif

                                {{-- Tombol Ganti Foto (Icon Kamera) --}}
                                <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow" 
                                       style="cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                                       title="Ganti Foto">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                            
                            {{-- Input File Tersembunyi --}}
                            <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*" onchange="previewImage(event)">
                            
                            <div class="mt-2 text-muted small">Format: JPG, PNG (Max 2MB)</div>
                        </div>

                        {{-- NAMA --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>

                        {{-- EMAIL (WAJIB ADA NAME="EMAIL" AGAR TIDAK ERROR) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            {{-- Perhatikan: name="email" saya tambahkan di sini --}}
                            <input type="email" name="email" class="form-control bg-light" value="{{ old('email', Auth::user()->email) }}" readonly>
                            <small class="text-muted">*Email tidak dapat diubah.</small>
                        </div>

                        {{-- ALAMAT --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap...">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-danger fw-bold mb-3"><i class="fas fa-lock me-1"></i> Ganti Password</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg font-weight-bold">
                                SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT UPDATE GAMBAR LANGSUNG --}}
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('avatar-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Mengganti src gambar dengan gambar yang baru dipilih
                preview.src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection