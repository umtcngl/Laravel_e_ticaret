@extends('layouts.main')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light rounded-pill mb-4">
    <div class="container">
        <a class="navbar-brand p-3 text-decoration-none" href="{{ route('satici.anasayfa') }}" style="color: #007bff;">
            <i class="fas fa-tachometer-alt me-2"></i>
            <span class="fw-bold">Satıcı Dashboard</span>
        </a>
        <div class="navbar-nav ml-auto">
            <a class="nav-link btn text-primary me-3" href="{{ route('satici.urunlerim') }}">
                <i class="fas fa-shopping-bag"></i> Ürünlerim
            </a>
            <a class="nav-link btn text-info me-3 position-relative" href="{{ route('bekleyen-siparisler') }}">
                <i class="fas fa-hourglass-half"></i> Bekleyen Siparişler
                <span class="badge bg-info rounded-circle position-absolute top-0 start-100 translate-middle">
                    {{ $bekleyenSiparisSayisi }}
                    <span class="visually-hidden">Bekleyen Siparişler</span>
                </span>
            </a>
            <a class="nav-link btn text-success me-3" href="{{ route('satici.satislarim') }}">
                <i class="fas fa-dollar-sign"></i> Satışlarım
            </a>
            <!-- İstediğiniz ekstra linkleri buraya ekleyebilirsiniz -->
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
@yield('satici-content')

@endsection
