@extends('layouts.musteri')

@section('title', 'SEPET')

@section('musteri-content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            @if($sepet->isEmpty())
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="m-5" style="font-size:50px">
                            <i class="fas fa-shopping-cart" style="color: #dc3545;"></i>
                            <span class="badge bg-danger">0</span>
                        </div>
                    <h2>Sepetiniz Boş</h2>
                    <p>Sepetinizde henüz ürün bulunmamaktadır.</p>
                    </div>
                </div>
            @else
                <h2>Sepetim</h2>
                <table class="table mt-4">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Ürün Adı</th>
                            <th>Fiyat</th>
                            <th>Miktar</th>
                            <th>Toplam</th>
                            <th class="d-flex justify-content-center align-items-center">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sepet as $item)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $item->urun->urunAdi }}</td>
                            <td>{{ $item->urun->fiyat }} ₺</td>
                            <td>{{ $item->miktar }}</td>
                            <td>{{ $item->urun->fiyat * $item->miktar }}</td>
                            <td class="d-flex justify-content-center align-items-center">
                                <form action="{{ route('sepet.arttir', $item->id) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success">+</button>
                                </form>
                                <form action="{{ route('sepet.eksilt', $item->id) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">-</button>
                                </form>
                                <form action="{{ route('sepet.kaldir', $item->id) }}" method="POST" class="me-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i> Sepetten Kaldır</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center align-items-center text-align-center mt-5">
                    <h1 class="me-5">Toplam Tutar: {{ $toplamTutar }} ₺</h1>
                    <form action="{{ route('siparis.olustur') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success"><i class="fas fa-shopping-cart"></i> Satın Al</button>
                    </form>

                </div>
            @endif
        </div>
    </div>
</div>
@endsection
