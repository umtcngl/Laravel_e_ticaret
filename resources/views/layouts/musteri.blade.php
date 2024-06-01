@extends('layouts.main')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light rounded-pill mb-4">
    <div class="container">
        <div class="col-lg-4 p-2">
            <a class="navbar-brand p-3 text-decoration-none" href="{{ route('musteri.anasayfa') }}" style="color: #007bff;">
                <i class="fas fa-dove me-2"></i>
                <span class="fw-bold">TİCARET KUŞU</span>
            </a>
        </div>
        <div class="col-lg-3">
            <div class="d-flex justify-content-center">
                <form class="d-flex">
                    <input class="form-control form-control-sm me-2 rounded-pill" type="search" style="width: 200px;" placeholder="Ara" aria-label="Search">
                </form>
            </div>
        </div>
        <div class="navbar-nav col-lg-5 d-flex justify-content-end align-items-center">
            <div class="nav-item dropdown">
                <span class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user" style="color: #007bff;"></i>
                    <span style="color: #007bff;">{{ $kullaniciAdi }}</span>
                </span>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                            </button>
                        </form>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('ayarlar') }}">
                            <i class="fas fa-cog"></i> Ayarlar
                        </a>
                    </li>
                </ul>
            </div>
            <span class="nav-item">
                <i class="fas fa-wallet ms-3" style="color: #28a745;"></i>
                <span style="color: #28a745;">{{ $bakiye }} ₺</span>
            </span>
            <a href="{{ route('siparislerim') }}" class="btn nav-item ms-3 position-relative text-decoration-none">
                <i class="fas fa-receipt" style="color: #ffc107;"></i>
                <span class="badge bg-warning position-absolute bottom-5 start-2 translate-middle">{{ $SiparislerimSayisi }}</span>
            </a>

            <a href="{{ route('sepet') }}" class="btn nav-item ms-3 position-relative text-decoration-none">
                <i class="fas fa-shopping-cart" style="color: #dc3545;"></i>
                <span class="badge bg-danger position-absolute bottom-5 start-2 translate-middle">{{ $sepetUrunSayisi }}</span>
            </a>
        </div>
    </div>
</nav>

<!-- Ana içerik -->
@yield('musteri-content')

@endsection
