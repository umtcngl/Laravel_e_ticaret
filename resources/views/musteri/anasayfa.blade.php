@extends('layouts.musteri')

@section('title', 'Anasayfa')

@section('musteri-content')
<div class="container">
    <div class="row">
        @foreach($kategoriler as $kategori)
        <div class="col-md-3 mb-4">
            <div class="card">
                <a href="{{ route('kategori.detay', $kategori->id) }}" class="btn card-body text-center">
                    @if ($kategori->icon)
                        <i class="{{ $kategori->icon }} fa-3x mb-2"></i>
                    @elseif ($kategori->icon_yolu)
                        <img src="{{ asset($kategori->icon_yolu) }}" alt="{{ $kategori->kategoriAdi }}" class="img-fluid mb-2">
                    @endif
                    <h5 class="card-title">{{ $kategori->kategoriAdi }}</h5>
                    <p class="card-text small">{{ $kategori->aciklama }}</p>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <hr>

    <div class="row">
        @foreach($urunler as $urun)
        <div class="col-md-2 mb-4">
            <div class="card">
                <a href="{{ route('urun.detay', $urun->id) }}" class="btn card-body text-center">
                    <img src="{{ asset($urun->resim_yolu) }}" alt="{{ $urun->urunAdi }}" class="img-fluid mb-2">
                    <h5 class="card-title">{{ $urun->urunAdi }}</h5>
                    <p class="card-text">{{ $urun->fiyat }}₺</p>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
