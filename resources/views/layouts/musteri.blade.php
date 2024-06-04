@extends('layouts.main')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light rounded-pill mb-4 text-align-center align-items-center justify-content-center">
    <div class="container">
        <div class=" d-flex justify-content-start">
            <a class="navbar-brand p-3 text-decoration-none" href="{{ route('musteri.anasayfa') }}" style="color: #007bff;">
                <i class="fas fa-dove me-2"></i>
                <span class="fw-bold">TİCARET KUŞU</span>
            </a>
        </div>
        <div class="mt-3 d-flex justify-content-center align-items-center">
            <div class="dropdown">
                <form id="searchForm" class="d-flex position-relative" action="{{ route('arama.sonuclari') }}" method="GET">
                    <input class="form-control form-control-sm me-2 rounded-pill" id="search" type="search" name="query" style="width: 200px;" placeholder="Ara" aria-label="Search">
                    <div class="dropdown-menu" id="searchResults" aria-labelledby="search" style="top: 45px; left: 0; right: auto; display: none;"></div>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-end align-items-center">
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
                <i class="fas fa-wallet ms-2" style="color: #28a745;"></i>
                <span style="color: #28a745;">{{ $bakiye }} ₺</span>
            </span>
            <span class="nav-item btn">
                <a href="{{ route('hesap.gecmisi') }}" class="text-decoration-none" style="color: #28a745;">
                    <i class="fas fa-history ms-2"></i> <!-- Yeni ikon -->
                </a>
            </span>

            <a href="{{ route('siparislerim') }}" class="btn nav-item ms-2 position-relative text-decoration-none">
                <i class="fas fa-receipt" style="color: #ffc107;"></i>
                <span class="badge bg-warning position-absolute bottom-5 start-2 translate-middle">{{ $siparislerimSayisi }}</span>
            </a>
            <a href="{{ route('sepet') }}" class="btn nav-item ms-2 position-relative text-decoration-none">
                <i class="fas fa-shopping-cart" style="color: #dc3545;"></i>
                <span class="badge bg-danger position-absolute bottom-5 start-2 translate-middle">{{ $sepetUrunSayisi }}</span>
            </a>
        </div>
    </div>
</nav>


<!-- Ana içerik -->
@yield('musteri-content')


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function () {
            const query = searchInput.value;

            if (query.length > 1) {
                fetch(`/search-live?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        if (data.length > 0) {
                            searchResults.style.display = 'block';
                            data.forEach(urun => {
                                const resultItem = document.createElement('a');
                                resultItem.classList.add('dropdown-item');
                                resultItem.href = `/urun/${urun.id}`;
                                resultItem.innerHTML = `
                                    <img src="${urun.resim_yolu}" alt="${urun.urunAdi}" class="img-thumbnail me-2" style="width: 50px; height: 50px;">
                                    <span style='font-size:12px'>${urun.urunAdi}</span>

                                `;
                                searchResults.appendChild(resultItem);
                            });
                        } else {
                            searchResults.style.display = 'none';
                        }
                    });
            } else {
                searchResults.style.display = 'none';
            }
        });

        document.addEventListener('click', function (event) {
            if (!searchResults.contains(event.target) && event.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });
    });
</script>
@endsection
