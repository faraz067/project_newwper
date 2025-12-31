@extends('layouts.app')

@section('content')
<div class="container mt-7">
  <h2 class="text-center mb-4">Jadwal Lapangan</h2>

  <table class="table table-bordered text-center">
    <thead class="bg-dark text-white">
      <tr>
        <th>Jam</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>08:00 - 09:00</td>
        <td class="text-success">Tersedia</td>
      </tr>
      <tr>
        <td>09:00 - 10:00</td>
        <td class="text-danger">Booked</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection
