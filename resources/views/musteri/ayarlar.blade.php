@extends('layouts.musteri')

@section('title', 'Ayarlar')

@section('musteri-content')
<div class="container mt-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-6">
            <div class="display-4 mb-3">Ayarlar</div>
            <div class="card-body">
                <form method="POST" action="{{ route('ayarlar.guncelle') }}">
                    @csrf
                    <div class="mb-3">
                        <input id="kullanici_adi" type="text" class="form-control" name="yeni_kullanici_adi" value="{{ Auth::user()->kullaniciAdi }}" placeholder="Yeni Kullanıcı Adı" required>
                    </div>

                    <div class="mb-3">
                        <input id="mevcut_sifre" type="password" class="form-control" name="mevcut_sifre" placeholder="Mevcut Şifre" required>
                    </div>

                    <div class="mb-3">
                        <input id="yeni_sifre" type="password" class="form-control" name="yeni_sifre" placeholder="Yeni Şifre" required>
                    </div>

                    <div class="mb-3">
                        <input id="yeni_sifre_tekrar" type="password" class="form-control" name="yeni_sifre_tekrar" placeholder="Yeni Şifre Tekrar" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-primary">Değişiklikleri Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
