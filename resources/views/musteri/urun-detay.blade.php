@extends('layouts.musteri')

@section('title', 'Ürün Detayı')

@section('musteri-content')
<div class="container d-flex justify-content-center mt-5">
    <div class="row">
        <div class="col-md-5 mb-4">
            <img src="{{ asset($urun->resim_yolu) }}" alt="{{ $urun->urunAdi }}" class="img-fluid">
        </div>
        <div class="col-md-7 mb-4 align-items-center">
            <h1>{{ $urun->urunAdi }}</h1>
            <p class="small"> {{ $urun->aciklama }}</p>
            <p><b>Satıcı:</b> {{ $urun->kullanici->kullaniciAdi }}</p>
            <p><b>Fiyat:</b> {{ $urun->fiyat }}₺</p>
            <p><b>Stok:</b> {{ $urun->stok }} Adet</p>


            <form method="post" action="{{ route('sepet.ekle', $urun->id) }}" class="mb-4">
                @csrf
                <div class="form-group">
                    <p class="text-muted">Miktar</p>
                    <input type="number" id="miktar" name="miktar" class="form-control" value="1" min="1" style="width: 90px;">
                </div>
                <button type="submit" class="btn btn-outline-primary mt-3">Sepete Ekle</button>
            </form>
        </div>
    </div>
</div>
@endsection
