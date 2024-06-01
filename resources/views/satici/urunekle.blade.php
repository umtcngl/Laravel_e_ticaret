@extends('layouts.satici')

@section('title', 'Ürün Ekle')

@section('satici-content')
<div class="d-flex justify-content-center align-items-center mt-5" style="height: 400px;">
    <div class="align-items-center justify-content-center mt-5" style="text-align:center; background-color:#f8f9fa; padding:50px; border-radius:25px;">
        <h1>Ürün Ekle</h1>

        <form method="post" action="{{ route('urun.ekle') }}" enctype="multipart/form-data" style="max-width:300px; margin:auto;">
            @csrf
            <div class="form-group">
                <input type="text" id="urunAdi" name="urunAdi" class="form-control" placeholder="Ürün Adı" style="margin-bottom:10px;" required />
            </div>
            <div class="form-group">
                <textarea id="aciklama" name="aciklama" class="form-control" placeholder="Açıklama" style="margin-bottom:10px;" required></textarea>
            </div>
            <div class="form-group">
                <input type="text" id="fiyat" name="fiyat" class="form-control" placeholder="Fiyat" style="margin-bottom:10px;" required />
            </div>
            <div class="form-group">
                <input type="text" id="stok" name="stok" class="form-control" placeholder="Stok" style="margin-bottom:10px;" required />
            </div>
            <div class="form-group">
                <select id="kategori_id" name="kategori_id" class="form-control" style="margin-bottom:10px;" required>
                    <option value="">Kategori Seçiniz</option>
                    @foreach($kategoriler as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->kategoriAdi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="file" id="resim" name="resim" class="form-control" style="margin-bottom:10px;" />
            </div>
            <button type="submit" class="btn btn-outline-primary" style="margin-bottom:10px;"><i class="fas fa-plus"></i> Ürün Ekle</button>
        </form>
    </div>
</div>
@endsection
