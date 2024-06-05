@extends('layouts.musteri')

@section('title', 'Anasayfa')
<?php
$favoriler = auth()->user()->favoriler()->with('urun')->get();
$favorisayisi = auth()->user()->favoriler()->count();
?>
@section('musteri-content')
<div class="container">
    <div class="row">
        @if(!$favoriler->isEmpty())
        <div class="col-md-3 mb-4">
            <div class="card text-info">
                <a href="{{ route('favoriler') }}" class="btn card-body text-center">
                    <i class="fas fa-star fa-3x mb-2"></i>
                    <h5 class="card-title">Favoriler</h5>
                    <p class="card-text small">*** <br> ( {{ $favorisayisi }} )</p>
                </a>
            </div>
        </div>
        @endif

        @foreach($kategoriler as $kategori)
        @php
            $kategoriUrunSayisi = $kategori->urunler->count();
        @endphp
        @if($kategoriUrunSayisi > 0)
        <div class="col-md-3 mb-4">
            <div class="card">
                <a href="{{ route('kategori.detay', $kategori->id) }}" class="btn card-body text-center">
                    @if ($kategori->icon)
                        <i class="{{ $kategori->icon }} fa-3x mb-2"></i>
                    @elseif ($kategori->icon_yolu)
                        <img src="{{ asset($kategori->icon_yolu) }}" alt="{{ $kategori->kategoriAdi }}" class="img-fluid mb-2">
                    @endif
                    <h5 class="card-title">{{ $kategori->kategoriAdi }}</h5>
                    <p class="card-text small">{{ $kategori->aciklama }} <br> ( {{ $kategoriUrunSayisi }} )</p>
                </a>
            </div>
        </div>
        @endif
        @endforeach
    </div>
@if(!$onerilenler->isEmpty())
    <!-- Önerilenler -->
    <h2>Önerilenler</h2>
    <hr>
    <div class="row row-cols-5 g-2 mb-5">
        <!-- Burada önerilen ürünleri göster -->
        @foreach($onerilenler as $onerilen)
        <div class="col">
            <div class="card h-100 position-relative">
                <!-- Mavi kutu -->
                <div class="d-flex position-absolute top-0 start-0 bg-primary text-white p-2 rounded-pill" style="z-index: 1;">
                    <i class="small fas fa-star me-3"> {{ isset($urunPuanlar[$onerilen->urun->id]) ? $urunPuanlar[$onerilen->urun->id] : 0 }}</i>
                    <i class="small fas fa-shopping-cart"> {{ isset($urunSiparisSayilari[$onerilen->urun->id]) ? $urunSiparisSayilari[$onerilen->urun->id] : 0 }}</i>
                </div>

                @if(isset($onerilen->urun))
                    <a href="{{ route('urun.detay', $onerilen->urun->id) }}" class="btn card-body text-center text-decoration-none">
                        <img src="{{ asset($onerilen->urun->resim_yolu) }}" alt="{{ $onerilen->urun->urunAdi }}" class="card-img-top mb-3">
                        <div class="card-body mt-auto">
                            <h5 class="card-title">{{ $onerilen->urun->urunAdi }}</h5>
                            <p class="card-text">{{ $onerilen->urun->fiyat }}₺</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    @endforeach
    </div>
@endif



 <!-- En yüksek puan alan ürünler -->
<h2>En Yüksek Puan Alanlar</h2>
<hr>
<div class="row row-cols-5 g-2">
    @foreach($enYuksekPuanAlanlar as $veri)
    <div class="col">
        <div class="card h-100 position-relative">
            <!-- Mavi kutu -->
            <div class="d-flex position-absolute top-0 start-0 bg-primary text-white p-2 rounded-pill" style="z-index: 1;">
                <i class="small fas fa-star me-3"> {{ isset($urunPuanlar[$veri->urun->id]) ? $urunPuanlar[$veri->urun->id] : 0 }}</i>
                <i class="small fas fa-shopping-cart"> {{ isset($urunSiparisSayilari[$veri->urun->id]) ? $urunSiparisSayilari[$veri->urun->id] : 0 }}</i>
            </div>

            @if(isset($veri->urun))
                <a href="{{ route('urun.detay', $veri->urun->id) }}" class="btn card-body text-center text-decoration-none">
                    <img src="{{ asset($veri->urun->resim_yolu) }}" alt="{{ $veri->urun->urunAdi }}" class="card-img-top mb-3">
                    <div class="card-body mt-auto">
                        <h5 class="card-title">{{ $veri->urun->urunAdi }}</h5>
                        <p class="card-text">{{ $veri->urun->fiyat }}₺</p>
                    </div>
                </a>
            @endif
        </div>
    </div>
@endforeach


</div>


    <!-- En Çok Satanlar -->
    <h2 class="mt-5">En Çok Satanlar</h2>
    <hr>
    <div class="row row-cols-5 g-2 mb-5">
        <!-- Burada en çok satan 4 ürünü göster -->
        @foreach($enCokSatanlar as $satan)
        <div class="col">
            <div class="card h-100 position-relative">
                <!-- Mavi kutu -->
                <div class="d-flex position-absolute top-0 start-0 bg-primary text-white p-2 rounded-pill" style="z-index: 1;">
                    <i class="small fas fa-star me-3"> {{ isset($urunPuanlar[$satan->urun->id]) ? $urunPuanlar[$satan->urun->id] : 0 }}</i>
                    <i class="small fas fa-shopping-cart"> {{ isset($urunSiparisSayilari[$satan->urun->id]) ? $urunSiparisSayilari[$satan->urun->id] : 0 }}</i>
                </div>

                @if(isset($satan->urun))
                    <a href="{{ route('urun.detay', $satan->urun->id) }}" class="btn card-body text-center text-decoration-none">
                        <img src="{{ asset($satan->urun->resim_yolu) }}" alt="{{ $satan->urun->urunAdi }}" class="card-img-top mb-3">
                        <div class="card-body mt-auto">
                            <h5 class="card-title">{{ $satan->urun->urunAdi }}</h5>
                            <p class="card-text">{{ $satan->urun->fiyat }}₺</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    @endforeach

    </div>

</div>
@endsection
