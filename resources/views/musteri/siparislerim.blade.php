@extends('layouts.musteri')
@section('title', 'Siparişlerim')
@section('musteri-content')

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h1>Siparişlerim</h1>
        </div>
        <div class="mt-3">
            <p><i class="fas fa-exclamation-circle" style="color:darkred "></i> Siparişinizi iptal ederseniz toplam tutarın %90'nını alırsınız!</p>
            <p><i class="fas fa-exclamation-circle" style="color:darkred "></i> Siparişiniz 'beklemede' değil ise iptal edemezsiniz!</p>
        </div>
    </div>

    @if($siparisler->isEmpty())
        <p>Aktif siparişiniz bulunmamaktadır.</p>
    @else
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">Sipariş No</th>
                <th scope="col">Toplam Tutar</th>
                <th scope="col">Durum</th>
                <th scope="col">Satıcı</th>
                <th scope="col">Tarih</th>
                <th scope="col">İşlem</th> <!-- Yeni eklenen sütun -->
            </tr>
        </thead>
        <tbody>
            @foreach($siparisler as $siparis)
                <tr data-toggle="collapse"  data-target="#collapse{{ $siparis->id }}" aria-expanded="false" aria-controls="collapse{{ $siparis->id }}">
                    <td>{{ $siparis->id }}</td>
                    <td><span>{{ $siparis->toplam_tutar }} ₺</span></td>
                    <td>{{$siparis->durum}}</td>
                    <td>{{ $siparis->kullanici->kullaniciAdi }}</td>
                    <td>{{ $siparis->siparis_tarihi}}</td>
                    <td> <!-- Yeni eklenen hücre -->
                        @if($siparis->durum == 'beklemede')
                            <form action="{{ route('siparis.iptal', $siparis->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    Siparişi İptal Et
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="p-0">
                        <div class="collapse" id="collapse{{ $siparis->id }}">
                            <div class="card card-body">
                                <ul>
                                    @foreach($siparis->siparisDetaylari as $detay)
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
