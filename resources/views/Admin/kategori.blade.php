@extends('layouts.admin')
@section('title', 'Kategori Yönetimi')
@section('admin-content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kategori Yönetimi</h1>
        <a href="{{ route('kategori.ekle') }}" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Kategori Ekle</a>
    </div>
    <div id="kategoriAccordion">
        @foreach($kategoriler as $kategori)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" id="kategori{{ $kategori->id }}">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $kategori->id }}" aria-expanded="true" aria-controls="collapse{{ $kategori->id }}">
                        @if($kategori->icon)
                            <i class="mr-2 {{ $kategori->icon }}"></i>
                        @elseif($kategori->icon_yolu)
                            <img src="{{ $kategori->icon_yolu }}" alt="Icon" style="width: 24px; height: 24px; margin-right: 8px;">
                        @endif
                        {{ $kategori->kategoriAdi }}
                    </button>
                </h2>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('kategori.duzenle', $kategori->id) }}" class="btn btn-outline-primary btn-sm me-2"><i class="fas fa-edit"></i> Düzenle</a>
                    <form method="POST" action="{{ route('kategori.sil', $kategori->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i> Sil</button>
                    </form>
                </div>
            </div>

            <div id="collapse{{ $kategori->id }}" class="collapse" aria-labelledby="kategori{{ $kategori->id }}" data-parent="#kategoriAccordion">
                <div class="card-body">
                    <table class="table table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Ürün Adı</th>
                                <th scope="col">Açıklama</th>
                                <th scope="col">Fiyat</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Yayınlayan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori->urunler as $urun)
                                <tr>
                                    <th scope="row">{{ $urun->id }}</th>
                                    <td>{{ $urun->urunAdi }}</td>
                                    <td>{{ $urun->aciklama }}</td>
                                    <td>{{ $urun->fiyat }}₺</td>
                                    <td>{{ $urun->stok }}</td>
                                    <td>{{ $urun->kullanici->kullaniciAdi }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
