@extends('layouts.admin')

@section('title', 'Kategori Düzenle')

@section('admin-content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Kategori Düzenle</h1>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-body">
                <form method="POST" action="{{ route('kategori.guncelle', $kategori->id) }}" class="mt-3">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <input type="text" id="kategoriAdi" name="kategoriAdi" class="form-control" placeholder="Kategori Adı" value="{{ $kategori->kategoriAdi }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <textarea id="aciklama" name="aciklama" class="form-control" placeholder="Açıklama" required>{{ $kategori->aciklama }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <input type="text" id="icon_yolu" name="icon_yolu" class="form-control" placeholder="Icon Yolu" value="{{ $kategori->icon_yolu }}">
                    </div>

                    <div class="form-group mb-3">
                        <input type="text" id="icon" name="icon" class="form-control" placeholder="Icon" value="{{ $kategori->icon }}">
                    </div>

                    <button type="submit" class="btn btn-outline-primary btn-block"><i class="fas fa-save"></i> Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
