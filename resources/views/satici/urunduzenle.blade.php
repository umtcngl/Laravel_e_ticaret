@extends('layouts.satici')

@section('title', 'Ürün Düzenle')

@section('satici-content')
<div class="container">
    <div class="d-flex justify-content-center align-items-center">
        <div class="align-items-center justify-content-center" style="text-align:center; background-color:#f8f9fa; padding:50px; border-radius:25px;">
            <h1>Ürün Düzenle</h1>

            <form method="post" action="{{ route('urun.duzenle', $urun->id) }}" enctype="multipart/form-data" style="max-width:300px; margin:auto;">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <input type="text" id="urunAdi" name="urunAdi" class="form-control" value="{{ $urun->urunAdi }}" required />
                </div>
                <div class="form-group mb-3">
                    <textarea id="aciklama" name="aciklama" class="form-control" required>{{ $urun->aciklama }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <input type="text" id="fiyat" name="fiyat" class="form-control" value="{{ $urun->fiyat }}" required />
                </div>
                <div class="form-group mb-3">
                    <input type="text" id="stok" name="stok" class="form-control" value="{{ $urun->stok }}" required />
                </div>
                <div class="form-group mb-3">
                    <select id="kategori_id" name="kategori_id" class="form-control" required>
                        <option value="">Kategori Seçiniz</option>
                        @foreach($kategoriler as $kategori)
                            <option value="{{ $kategori->id }}" @if($urun->kategori_id == $kategori->id) selected @endif>{{ $kategori->kategoriAdi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>{{ $urun->resim_yolu }}</label>
                    <input type="file" id="resim" name="resim" class="form-control" />
                </div>
                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-save"></i> Kaydet</button>
            </form>
        </div>
    </div>
</div>
@endsection
