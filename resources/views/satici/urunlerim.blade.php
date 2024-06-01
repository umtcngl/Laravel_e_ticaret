@extends('layouts.satici')

@section('title', 'Ürünlerim')

@section('satici-content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Ürünlerim</h1>
        <a href="{{ route('urun.ekle') }}" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Ürün Ekle</a>
    </div>
    <p>Burada eklediğiniz ürünleri görüntüleyebilir, düzenleyebilir ve silebilirsiniz.</p>

    <!-- Kullanıcıya Ait Ürünler Tablosu -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ürün Adı</th>
                    <th scope="col">Açıklama</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Fiyat</th>
                    <th scope="col">Stok</th>
                    <th scope="col">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($urunler as $urun)
                <tr>
                    <th scope="row">{{ $urun->id }}</th>
                    <td>{{ $urun->urunAdi }}</td>
                    <td>{{ $urun->aciklama }}</td>
                    <td>{{ $urun->kategori->kategoriAdi }}</td>
                    <td>{{ $urun->fiyat }}₺</td>
                    <td>{{ $urun->stok }}</td>
                    <td>
                        <a href="{{ route('urun.duzenle', $urun->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Düzenle</a>
                        <form action="{{ route('urun.sil', $urun->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Sil</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
