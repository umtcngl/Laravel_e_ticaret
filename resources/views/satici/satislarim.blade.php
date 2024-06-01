@extends('layouts.satici')

@section('title', 'Satışlarım')

@section('satici-content')

<div class="container mt-5">
    <h1 class="mb-4">Satışlarım</h1>

    @if($satislar->isEmpty())
        <p>Henüz bir satışınız bulunmamaktadır.</p>
    @else
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Sipariş No</th>
                    <th scope="col">Toplam Tutar</th>
                    <th scope="col">Sipariş Tarihi</th>
                    <th scope="col">Teslim Tarihi</th>
                    <th scope="col">Alan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($satislar as $satis)
                    <tr data-toggle="collapse"  data-target="#collapse{{ $satis->id }}" aria-expanded="false" aria-controls="collapse{{ $satis->id }}">
                        <td>{{ $satis->id }}</td>
                        <td><span style="color: green;"><i class="fas fa-plus fa-sm"></i> {{ $satis->toplam_tutar }} ₺</span></td>
                        <td>{{ $satis->siparis_tarihi }}</td>
                        <td>{{ $satis->updated_at }}</td>
                        <td>{{ $satis->kullanici->kullaniciAdi }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="p-0">
                            <div class="collapse" id="collapse{{ $satis->id }}">
                                <div class="card card-body">
                                    <ul>
                                        @foreach($satis->siparisDetaylari as $detay)
                                            <li class="d-inline-block me-3">
                                                @if($detay->urun->resim_yolu)
                                                    <img src="{{ asset($detay->urun->resim_yolu) }}" alt="{{ $detay->urun->urunAdi }}" width="50">
                                                @else
                                                    Resim Yok
                                                @endif
                                                {{ $detay->urun->urunAdi }} - {{ $detay->miktar }} adet
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
