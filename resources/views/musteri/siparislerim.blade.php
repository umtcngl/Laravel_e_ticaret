@extends('layouts.musteri')
@section('title', 'Siparişlerim')
@section('musteri-content')

<div class="container mt-5">
    <h1 class="mb-4">Siparişlerim</h1>

    @if($siparisler->isEmpty())
        <p>Henüz siparişiniz bulunmamaktadır.</p>
    @else
        <div class="accordion" id="siparisAccordion">
            @foreach($siparisler as $siparis)
                @if($siparis->siparisDetaylari->isNotEmpty())
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white" id="siparis{{ $siparis->id }}">
                            <button class="btn text-white" type="button" data-toggle="collapse" data-target="#collapse{{ $siparis->id }}" aria-expanded="true" aria-controls="collapse{{ $siparis->id }}">
                                Sipariş No: {{ $siparis->id }} -
                                Toplam Tutar: {{ $siparis->toplam_tutar }} ₺ -
                                Durum: {{ $siparis->durum }}
                                @if($siparis->siparisDetaylari->first()->urun->kullanici)
                                    - Satıcı: {{ $siparis->siparisDetaylari->first()->urun->kullanici->kullaniciAdi }}
                                @endif
                                - Tarih: {{ $siparis->created_at->format('d.m.Y H:i') }}
                            @if($siparis->durum == 'beklemede')
                                <form action="{{ route('siparis.iptal', $siparis->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                    Siparişi İptal Et
                                    </button>
                                </form>
                            @endif
                        </button>
                        </div>

                        <div id="collapse{{ $siparis->id }}" class="collapse" aria-labelledby="siparis{{ $siparis->id }}" data-parent="#siparisAccordion">
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Ürün Adı</th>
                                            <th scope="col">Miktar</th>
                                            <th scope="col">Toplam Fiyat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siparis->siparisDetaylari as $index => $detay)
                                            <tr>
                                                <th scope="row">{{ $index + 1 }}</th>
                                                <td>{{ $detay->urun->urunAdi }}</td>
                                                <td>{{ $detay->miktar }}</td>
                                                <td>{{ $detay->miktar * $detay->urun->fiyat }} ₺</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
