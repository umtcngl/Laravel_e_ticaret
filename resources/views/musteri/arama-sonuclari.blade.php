@extends('layouts.musteri')
@section('title','Arama Sonuçları')
@section('musteri-content')
<div class="container">
    <h2 class="mb-3">Arama Sonuçları</h2>
    <hr>
    <div class="row">
        @foreach($urunler as $urun)
        <div class="col-md-3 mb-4">
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
