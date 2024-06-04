@extends('layouts.musteri')

@section('title', 'Ürün Detayı')

@section('musteri-content')
@if($dahaOnceAlmisMi && !$yapilmisYorum)
    <div class="row mt-5">
        <div class="col-md-4 mb-4 d-flex position-relative">
            <img src="{{ asset($urun->resim_yolu) }}" alt="{{ $urun->urunAdi }}" class="img-fluid">
            <!-- Bilgi Kısmı -->
            <span class="d-flex position-absolute top-0 start-0 bg-info text-white p-2 rounded-pill" style="z-index: 1;">
                <i class="fas fa-star me-3"> {{ isset($urunPuanlar[$urun->id]) ? $urunPuanlar[$urun->id] : 0 }}</i>
                    <i class="fas fa-shopping-cart"> {{ isset($urunSiparisSayilari[$urun->id]) ? $urunSiparisSayilari[$urun->id] : 0 }}</i>
            </span>
            <!-- Favori Ekle/Çıkar Formu -->
            <form method="post" action="{{ route('favori.toggle', $urun->id) }}">
                @csrf
                @php
                    $favori = Auth::check() ? Auth::user()->favoriler()->where('urun_id', $urun->id)->first() : null;
                @endphp
                <button type="submit" class="btn btn-info p-2 mt-2" style="font-size:25px;color:#107df2;background-color: transparent; border: none;">
                    <i class="{{ $favori ? 'fas fa-star' : 'far fa-star' }}"></i>
                </button>
            </form>
        </div>
        <div class="col-md-5 mb-4">
            <div class="ms-4">
                <h1>{{ $urun->urunAdi }}</h1>
                <p class="small">{{ $urun->aciklama }}</p>
                <p><b>Satıcı:</b> {{ $urun->kullanici->kullaniciAdi }}</p>
                <p><b>Fiyat:</b> {{ $urun->fiyat }}₺</p>
                <p><b>Stok:</b> {{ $urun->stok }} Adet</p>

                <!-- Sepete Ekle Formu -->
                <form method="post" action="{{ route('sepet.ekle', $urun->id) }}" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <p class="text-muted">Miktar</p>
                        <input type="number" id="miktar" name="miktar" class="form-control" value="1" min="1" style="width: 90px;">
                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-3"><i class="fas fa-cart-plus me-2"></i>Sepete Ekle</button>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <!-- Yorum Ekle Formu -->
            <form method="post" action="{{ route('yorum.yap', $urun->id) }}">
                @csrf
                <div class="form-group">
                    <label for="icerik" class="mb-3">Yorumunuz</label>
                    <textarea class="form-control" id="icerik" name="icerik" rows="3" required></textarea>
                </div>
                <div class="form-group mt-4">
                    <div class="form-group">
                        <label for="puan">Puanınız</label>
                        <output class="ms-3 mb-3" id="selectedPuan">0</output>
                        <input type="range" class="form-range" id="puan" name="puan" min="0" max="10" step="1" value="0">
                      </div>
                </div>
                <button type="submit" class="btn btn-outline-dark mt-4"><i class="fas fa-comment me-2"></i>Yorum Yap</button>
            </form>
        </div>
    </div>
@else
    <div class="row mt-5">
        <div class="col-md-4 mb-4 d-flex position-relative">
            <img src="{{ asset($urun->resim_yolu) }}" alt="{{ $urun->urunAdi }}" class="img-fluid">
            <!-- Bilgi Kısmı -->
            <span class="d-flex position-absolute top-0 start-0 bg-info text-white p-2 rounded-pill" style="z-index: 1;">
                <i class="fas fa-star me-3"> {{ isset($urunPuanlar[$urun->id]) ? $urunPuanlar[$urun->id] : 0 }}</i>
                    <i class="fas fa-shopping-cart"> {{ isset($urunSiparisSayilari[$urun->id]) ? $urunSiparisSayilari[$urun->id] : 0 }}</i>
            </span>
            <!-- Favori Ekle/Çıkar Formu -->
            <form method="post" action="{{ route('favori.toggle', $urun->id) }}">
                @csrf
                @php
                    $favori = Auth::check() ? Auth::user()->favoriler()->where('urun_id', $urun->id)->first() : null;
                @endphp
                <button type="submit" class="btn btn-info p-2 mt-2" style="font-size:25px;color:#107df2;background-color: transparent; border: none;">
                    <i class="{{ $favori ? 'fas fa-star' : 'far fa-star' }}"></i>
                </button>
            </form>
        </div>

        <div class="col-md-8 mb-4">
            <div class="ms-4">
                <h1>{{ $urun->urunAdi }}</h1>
                <p class="small">{{ $urun->aciklama }}</p>
                <p><b>Satıcı:</b> {{ $urun->kullanici->kullaniciAdi }}</p>
                <p><b>Fiyat:</b> {{ $urun->fiyat }}₺</p>
                <p><b>Stok:</b> {{ $urun->stok }} Adet</p>

                <!-- Sepete Ekle Formu -->
                <form method="post" action="{{ route('sepet.ekle', $urun->id) }}" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <p class="text-muted">Miktar</p>
                        <input type="number" id="miktar" name="miktar" class="form-control" value="1" min="1" style="width: 90px;">
                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-3"><i class="fas fa-cart-plus me-2"></i>Sepete Ekle</button>
                </form>
            </div>
        </div>
    </div>
@endif

@if($yorumlar->isEmpty())
    <div class="row mt-5">
        <div class="col-md-12">
            <p>Bu ürün için henüz yorum yapılmamış.</p>
        </div>
    </div>
@else
    <!-- Yorumlar ve Derecelendirmeler -->
    <div class="row mt-5 mb-5">
        <div class="col-12">
            <h2 class="mb-5">Yorumlar ve Derecelendirmeler</h2>
            <div class="row">
                @foreach ($urun->yorumlar as $yorum)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><strong>{{ $yorum->kullanici->kullaniciAdi }}</strong></h5>
                                <p class="card-text">{{ $yorum->icerik }}</p>
                                @if($yorum->puan)
                                    <p class="card-text"><strong>Puan:</strong> {{ $yorum->puan }}/10</p>
                                @else
                                    <p class="card-text"><strong>Puan:</strong> Henüz puan verilmedi</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
      const puanSlider = document.getElementById('puan'); // Değiştirilen kısım
      const selectedPuan = document.getElementById('selectedPuan');

      puanSlider.addEventListener('input', function() {
        selectedPuan.textContent = puanSlider.value;
      });
    });
    $(document).ready(function() {
      // Sayfa yüklenirken başlangıç zamanını kaydet
      let startTime = new Date().getTime();

      $(window).on("beforeunload", function() {
        let endTime = new Date().getTime(); // Sayfadan çıkış zamanı
        let duration = (endTime - startTime) / 1000; // Süreyi saniye olarak hesapla
        let url = window.location.href;
        let pathArray = window.location.pathname.split('/');
        let filteredUrl = `${pathArray[1]}/${pathArray[2]}`;
        let urunId = {{ $urun->id }};
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
