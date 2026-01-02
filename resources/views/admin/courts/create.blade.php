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
                    <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-control-label">Nama Lapangan</label>
                            <input class="form-control" type="text" name="name" required placeholder="Contoh: Lapangan A">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Jenis Lapangan (Type)</label>
                            <select class="form-control" name="type" required>
                                <option value="Futsal">Futsal</option>
                                <option value="Badminton">Badminton</option>
                                <option value="Basket">Basket</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Harga per Jam (Rp)</label>
                            <input class="form-control" type="number" name="price_per_hour" required>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto Lapangan</label>
                            <input class="form-control" type="file" name="photo" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm ms-auto mt-4">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection