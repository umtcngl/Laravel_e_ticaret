@extends('layouts.main')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light rounded-pill mb-4">
    <div class="container">
        <a class="navbar-brand p-3 text-decoration-none" href="{{ route('admin.anasayfa') }}" style="color: #007bff;">
            <i class="fas fa-tachometer-alt me-2"></i>
            <span class="fw-bold">Admin Dashboard</span>
        </a>
        <div class="navbar-nav ml-auto">
            <a class="nav-link btn text-primary me-3" href="{{ route('admin.kullanici') }}">
                <i class="fas fa-users"></i> Kullanıcı Yönetimi
            </a>
            <a class="nav-link btn text-info me-3" href="{{ route('admin.kategori') }}">
                <i class="fas fa-tags"></i> Kategori Yönetimi
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Ana içerik -->
@yield('admin-content')

@endsection
