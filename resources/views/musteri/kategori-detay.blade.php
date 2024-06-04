@extends('layouts.musteri')

@section('title', $kategori->kategoriAdi)

@section('musteri-content')
<div class="container">
    <h1 class="mb-4">
        @if ($kategori->icon)
        <i class="{{ $kategori->icon }} mb-2"></i>
        @elseif ($kategori->icon_yolu)
            <img src="{{ asset($kategori->icon_yolu) }}" alt="{{ $kategori->kategoriAdi }}" class="img-fluid mb-2">
        @endif
        {{ $kategori->kategoriAdi }}
    </h1>
    <hr>
    <div class="row row-cols-5">
        @foreach($urunler as $urun)
        <div class="col mb-4">
            <div class="card h-100 position-relative">
                <!-- Mavi kutu -->
                <div class="d-flex position-absolute top-0 start-0 bg-primary text-white p-2 rounded-pill" style="z-index: 1;">
                    <span class="small">
                        <i class="fas fa-star me-2">
                        {{ isset($urunPuanlar[$urun->id]) ? $urunPuanlar[$urun->id] : 0 }}</i>
                    </span>
                    <span class="small">
                        <i class="fas fa-shopping-cart">
                        {{ isset($urunSiparisSayilari[$urun->id]) ? $urunSiparisSayilari[$urun->id] : 0 }}</i>
                    </span>
                </div>

                <a href="{{ route('urun.detay', $urun->id) }}" class="btn card-body text-center text-decoration-none">
                    <img src="{{ asset($urun->resim_yolu) }}" alt="{{ $urun->urunAdi }}" class="card-img-top mb-3">
                    <div class="card-body mt-auto">
                        <h5 class="card-title">{{ $urun->urunAdi }}</h5>
                        <p class="card-text">{{ $urun->fiyat }}₺</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach

    </div>
</div>
<script>
    $(document).ready(function() {
      // Sayfa yüklenirken başlangıç zamanını kaydet
      let startTime = new Date().getTime();

      $(window).on("beforeunload", function() {
        let endTime = new Date().getTime(); // Sayfadan çıkış zamanı
        let duration = (endTime - startTime) / 1000; // Süreyi saniye olarak hesapla
        let fullUrl = window.location.href;
        let pathArray = window.location.pathname.split('/');
        let filteredUrl = `${pathArray[1]}/${pathArray[2]}`;
        let urunId = null; // urun_id'yi boş gönderiyoruz
        let csrfToken = "{{ csrf_token() }}"; // CSRF token'ı yazdırın

        $.ajax({
          url: "/musteri/sayfa-suresi-kaydet",
          method: "POST",
          data: {
            _token: csrfToken, // Token'ı isteğe ekleyin
            url: filteredUrl,
            duration: duration,
            urunId: urunId
          },
          success: function() {
            console.log("Sayfa kalma süresi kaydedildi");
          },
          error: function() {
            console.error("Sayfa kalma süresi kaydedilemedi");
          }
        });
      });
    });
</script>

@endsection
