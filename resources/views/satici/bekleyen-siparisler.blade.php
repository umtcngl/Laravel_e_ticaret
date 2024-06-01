@extends('layouts.satici')

@section('title', 'Bekleyen Siparişler')

@section('satici-content')

<div class="container mt-5">
    <h1 class="mb-4">Bekleyen Siparişler</h1>

    @if($bekleyenSiparisler->isEmpty())
        <p>Bekleyen siparişiniz bulunmamaktadır.</p>
    @else
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Sipariş No</th>
                    <th scope="col">Toplam Tutar</th>
                    <th scope="col">Durum</th>
                    <th scope="col">Tarih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bekleyenSiparisler as $siparis)
                    <tr data-toggle="collapse"  data-target="#collapse{{ $siparis->id }}" aria-expanded="false" aria-controls="collapse{{ $siparis->id }}">
                        <td>{{ $siparis->id }}</td>
                        <td>{{ $siparis->toplam_tutar }} ₺</td>
                        <td>
                            {{ $siparis->durum }}
                        </td>
                        <td>{{ $siparis->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="p-0">
                            <div class="collapse" id="collapse{{ $siparis->id }}">
                                <div class="card card-body">
                                    <div class="d-flex">
                                        <h5 class="me-5">Detaylı Sipariş</h5>
                                        <div class="dropup">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton_{{ $siparis->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ $siparis->durum }}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $siparis->id }}">
                                                <a class="dropdown-item" href="#" onclick="selectDurum('{{ $siparis->id }}', 'beklemede')">Beklemede</a>
                                                <a class="dropdown-item" href="#" onclick="selectDurum('{{ $siparis->id }}', 'hazırlanıyor')">Hazırlanıyor</a>
                                                <a class="dropdown-item" href="#" onclick="selectDurum('{{ $siparis->id }}', 'kargoya verildi')">Kargoya Verildi</a>
                                                <a class="dropdown-item" href="#" onclick="selectDurum('{{ $siparis->id }}', 'teslim edildi')">Teslim Edildi</a>
                                            </div>
                                            <form action="{{ route('satici.siparis.durum.guncelle', ['id' => $siparis->id]) }}" method="POST" id="durumGuncelleForm_{{ $siparis->id }}">
                                                @csrf
                                                <input type="hidden" name="durum" id="selectedDurum_{{ $siparis->id }}">
                                            </form>
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Ürün Adı</th>
                                                <th scope="col">Miktar</th>
                                                <th scope="col">Ara Toplam</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siparis->siparisDetaylari as $detay)
                                                <tr>
                                                    <td>{{ $detay->urun->urunAdi }}</td>
                                                    <td>{{ $detay->miktar }}</td>
                                                    <td>{{ $detay->miktar * $detay->urun->fiyat }} ₺</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
<script>
    function selectDurum(siparisId, durum) {
        var currentDurum = document.getElementById('dropdownMenuButton_' + siparisId).innerText.trim();
        if (currentDurum.toLowerCase() === durum.toLowerCase()) {
            return; // Aynı durum seçildiğinde submit yapma
        }
        // Seçilen durumu gizli input alanına ata
        document.getElementById('selectedDurum_' + siparisId).value = durum;
        // Formu gönder
        document.getElementById('durumGuncelleForm_' + siparisId).submit();
    }
</script>

@endsection
