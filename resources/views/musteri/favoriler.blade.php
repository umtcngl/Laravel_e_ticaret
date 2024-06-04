@extends('layouts.musteri')

@section('title', 'Favoriler')

@section('musteri-content')
<div class="container">
    <h1 class="mb-4 text-info">
        <i class="fas fa-star mb-2"></i>
        Favoriler
    </h1>
    <hr>
    <div class="row">
        @foreach($favoriler as $favori)
        <div class="col-md-3 mb-4">
            <div class="card">
                            <!-- Favori Ekle/Çıkar Formu -->
            <form method="post" action="{{ route('favori.toggle', $favori->urun->id) }}">
                @csrf
                @php
                    $favori = Auth::check() ? Auth::user()->favoriler()->where('urun_id', $favori->urun->id)->first() : null;
                @endphp
                <button type="submit" class="position-absolute top-0 start-0 btn btn-info p-2 mt-2" style="font-size:25px;color:#107df2;background-color: transparent; border: none;z-index:1">
                    <i class="{{ $favori ? 'fas fa-star' : 'far fa-star' }}"></i>
                </button>
            </form>
                <a href="{{ route('urun.detay', $favori->urun->id) }}" class="btn card-body text-center">
                    <img src="{{ asset($favori->urun->resim_yolu) }}" alt="{{ $favori->urun->urunAdi }}" class="img-fluid mb-2">
                    <h5 class="card-title">{{ $favori->urun->urunAdi }}</h5>
                    <p class="card-text">{{ $favori->urun->fiyat }}₺</p>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
