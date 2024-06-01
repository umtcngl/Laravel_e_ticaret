@extends('layouts.admin')

@section('title', 'Kategori Ekle')

@section('admin-content')
<div class="d-flex justify-content-center align-items-center mt-5" style="height: 400px;">
    <div class="align-items-center justify-content-center mt-5" style="text-align:center; background-color:#f8f9fa; padding:50px; border-radius:25px;">
        <h1>Kategori Ekle</h1>

        <form method="post" action="{{ route('kategori.kaydet') }}" style="max-width:300px; margin:auto;">
            @csrf
            <div class="form-group">
                <input type="text" id="kategoriAdi" name="kategoriAdi" class="form-control" placeholder="Kategori Adı" style="margin-bottom:10px;" required />
            </div>
            <div class="form-group">
                <textarea id="aciklama" name="aciklama" class="form-control" placeholder="Açıklama" style="margin-bottom:10px;" required></textarea>
            </div>
            <div class="form-group">
                <input type="text" id="icon_yolu" name="icon_yolu" class="form-control" placeholder="Icon Yolu" style="margin-bottom:10px;" />
            </div>
            <div class="form-group">
                <input type="text" id="icon" name="icon" class="form-control" placeholder="Icon" style="margin-bottom:10px;" />
            </div>
            <button type="submit" class="btn btn-outline-primary" style="margin-bottom:10px;"><i class="fas fa-plus"></i> Kategori Ekle</button>
        </form>
    </div>
</div>
@endsection
