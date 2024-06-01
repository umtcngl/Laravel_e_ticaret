@extends('layouts.main')

@section('title', 'Giriş Yap')

@section('content')
<div class="d-flex justify-content-center align-items-center mt-5" style="height: 400px;">
    <div class="align-items-center justify-content-center mt-5" style="text-align:center; background-color:#f8f9fa; padding:50px; border-radius:25px;">
        <h1>Giriş Yap</h1>

        <form method="post" action="{{ route('giris') }}" style="max-width:300px; margin:auto;">
            @csrf
            <div class="form-group">
                <input type="text" id="username" name="kullaniciAdi" class="form-control" placeholder="Kullanıcı Adı" style="margin-bottom:10px;" required />
            </div>
            <div class="form-group">
                <input type="password" id="password" name="sifre" class="form-control" placeholder="Şifre" style="margin-bottom:10px;" required />
            </div>
            <button type="submit" class="btn btn-outline-primary" style="margin-bottom:10px;"><i class="fas fa-sign-in-alt"></i> Giriş Yap</button>
        </form>

        <div>
            <a href="{{ route('kayit') }}" class="btn btn-outline-success"><i class="fas fa-user-plus"></i> Kayıt Ol</a>
        </div>

    </div>
</div>

@endsection
