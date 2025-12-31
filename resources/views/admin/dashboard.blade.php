<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app') <!-- atau layout admin-mu -->

@section('content')
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
@endsection
