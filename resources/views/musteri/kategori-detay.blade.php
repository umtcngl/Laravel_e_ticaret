@extends('layouts.musteri')

@section('title', $kategori->kategoriAdi)

@section('musteri-content')
<div class="container">
    <h1 class="mb-4">{{ $kategori->kategoriAdi }}</h1>
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
