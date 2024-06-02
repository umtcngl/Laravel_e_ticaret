@extends('layouts.musteri')

@section('title', 'Hesap Geçmişi')

@section('musteri-content')
<div class="container mt-5">
    <h1 class="mb-4">Hesap Geçmişi</h1>

    @if($gecmisSiparisler->isEmpty())
        <p>Geçmişte herhangi bir siparişiniz bulunmamaktadır.</p>
    @else
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Tarih</th>
                    <th scope="col">Durum</th>
                    <th scope="col">Satıcı</th>
                    <th scope="col">Toplam Tutar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gecmisSiparisler as $siparis)
                    <tr data-bs-toggle="collapse" data-bs-target="#collapse{{ $siparis->id }}" aria-expanded="false" aria-controls="collapse{{ $siparis->id }}">
                        <td class="small">{{ $siparis->created_at->diffForHumans() }}</td>
                        <td>{{ $siparis->durum }}</td>
                        <td>{{ $siparis->siparisDetaylari->first()->urun->kullanici->kullaniciAdi }}</td>
                        <td><span style="color: #dc3545;"><i class="fas fa-minus fa-sm"></i> {{ $siparis->toplam_tutar }} ₺</span></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="p-0">
                            <div class="collapse" id="collapse{{ $siparis->id }}">
                                <div class="card card-body">
                                    <div class="row">
                                        @foreach($siparis->siparisDetaylari as $detay)
                                            <div class="col-2 d-flex flex-column align-items-center mb-3">
                                                @if($detay->urun->resim_yolu)
                                                    <img src="{{ asset($detay->urun->resim_yolu) }}" alt="{{ $detay->urun->urunAdi }}" class="img-thumbnail mb-2" style="width: 40%;">
                                                @else
                                                    <img src="path_to_placeholder_image" alt="Resim Yok" class="img-thumbnail mb-2" style="width: 100%;">
                                                @endif
                                                <div class="text-center small">
                                                    <p>{{ $detay->urun->urunAdi }}</p>
                                                    <p>{{ $detay->miktar }} adet</p>
                                                    <p>{{ $detay->urun->fiyat }} ₺</p>
                                                    <p>{{ $detay->miktar * $detay->urun->fiyat }} ₺</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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
