@extends('layouts.app')

@section('content')
<div class="container mt-7">
  <h2 class="text-center mb-4">Daftar Lapangan</h2>

  <div class="row">
    <div class="col-md-4">
      <div class="card shadow">
        <img src="{{ asset('assets/img/futsal.jpg') }}" class="card-img-top">
        <div class="card-body">
          <h5>Lapangan Futsal</h5>
          <p>Rumput sintetis, indoor</p>
          <a href="#" class="btn btn-success btn-sm">Lihat Jadwal</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow">
        <img src="{{ asset('assets/img/badminton.jpg') }}" class="card-img-top">
        <div class="card-body">
          <h5>Lapangan Badminton</h5>
          <p>Karpet standar nasional</p>
          <a href="#" class="btn btn-success btn-sm">Lihat Jadwal</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
