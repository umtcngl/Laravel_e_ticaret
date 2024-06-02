<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MusteriController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SaticiController;

use Illuminate\Support\Facades\Route;

// Anasayfa yönlendirmesi
Route::redirect('/', '/giris');

// Oturum açma ve çıkma işlemleri
Route::get('/giris', [LoginController::class, 'showLoginForm'])->name('giris');
Route::post('/giris', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Kullanıcı kayıt işlemleri
Route::get('/kayit', [RegisterController::class, 'showRegistrationForm'])->name('kayit');
Route::post('/kayit', [RegisterController::class, 'register']);

// Oturum gerektiren sayfalar
Route::middleware(['auth'])->group(function () {
    // Müşteri sayfaları
    Route::get('/anasayfa', [MusteriController::class, 'anasayfa'])->name('musteri.anasayfa');
    Route::get('/kategori/{id}', [MusteriController::class, 'kategoriDetay'])->name('kategori.detay');
    Route::get('/urun/{id}', [MusteriController::class, 'urunDetay'])->name('urun.detay');
    Route::post('/urun/{id}/yorum-yap', [MusteriController::class, 'yorumYap'])->name('yorum.yap');
    Route::post('/favori/toggle/{urun}', [MusteriController::class, 'favoriToggle'])->name('favori.toggle');
    Route::get('/favoriler', [MusteriController::class, 'favoriler'])->name('favoriler');

    Route::post('/sepet/ekle/{urunId}', [MusteriController::class, 'sepeteEkle'])->name('sepet.ekle');
    Route::get('/sepet', [MusteriController::class, 'sepet'])->name('sepet');
    Route::post('/sepet/arttir/{id}', [MusteriController::class, 'arttir'])->name('sepet.arttir');
    Route::post('/sepet/eksilt/{id}', [MusteriController::class, 'eksilt'])->name('sepet.eksilt');
    Route::delete('/sepet/kaldir/{id}', [MusteriController::class, 'kaldir'])->name('sepet.kaldir');
    Route::post('/siparis-olustur', [MusteriController::class, 'siparisOlustur'])->name('siparis.olustur');

    Route::get('/siparislerim', [MusteriController::class, 'siparislerim'])->name('siparislerim');
    Route::delete('/siparislerim/siparis/sil/{id}', [MusteriController::class, 'siparis_sil'])->name('siparis.iptal');
    Route::post('/satici/siparis/durum-guncelle/{id}', 'SaticiController@siparisDurumGuncelle')->name('satici.siparis.durum.guncelle');

    Route::get('/search-live', [MusteriController::class, 'searchLive'])->name('search.live');
    Route::get('/arama-sonuclari', [MusteriController::class, 'aramaSonuclari'])->name('arama.sonuclari');

    Route::get('/hesap-gecmisi', [MusteriController::class, 'hesapGecmisi'])->name('hesap.gecmisi');

    Route::get('/ayarlar', [MusteriController::class, 'ayarlar'])->name('ayarlar');
    Route::post('/ayarlar/guncelle', [MusteriController::class, 'ayarlarGuncelle'])->name('ayarlar.guncelle');

    // Admin sayfaları
    Route::prefix('admin')->group(function () {
        Route::get('/anasayfa', [AdminController::class, 'anasayfa'])->name('admin.anasayfa');
        Route::get('/kullanici', [AdminController::class, 'kullanici'])->name('admin.kullanici');
        Route::get('/kategori', [AdminController::class, 'kategori'])->name('admin.kategori');
        Route::get('/kategori/ekle', [AdminController::class, 'kategoriEkle'])->name('kategori.ekle');
        Route::post('/kategori/kaydet', [AdminController::class, 'kategoriKaydet'])->name('kategori.kaydet');
        Route::delete('/kategori/sil/{id}', [AdminController::class, 'kategoriSil'])->name('kategori.sil');
        Route::get('/kategori/duzenle/{id}', [AdminController::class, 'duzenle'])->name('kategori.duzenle');
        Route::put('/kategori/guncelle/{id}', [AdminController::class, 'guncelle'])->name('kategori.guncelle');

        Route::post('/kullanici/{id}/rol', [AdminController::class, 'kullaniciRol'])->name('admin.kullanici.rol');
        Route::post('/kullanici/{id}/bakiye/kaydet', [AdminController::class, 'kullaniciBakiyeKaydet'])->name('admin.kullanici.bakiye.kaydet');
        Route::post('/kullanici/sil/{id}', [AdminController::class, 'kullaniciSil'])->name('admin.kullanici.sil');
    });

    // Satici sayfaları
    Route::prefix('satici')->group(function () {
        Route::get('/anasayfa', [SaticiController::class, 'anasayfa'])->name('satici.anasayfa');

        Route::get('/urunlerim', [SaticiController::class, 'urunlerim'])->name('satici.urunlerim');
        Route::get('/satici/urunlerim', [SaticiController::class, 'urunlerim'])->name('urunlerim');
        Route::get('/satici/urun/ekle', [SaticiController::class, 'urunEkleForm'])->name('urun.ekle.form');
        Route::post('/satici/urun/ekle', [SaticiController::class, 'urunEkle'])->name('urun.ekle');
        Route::get('/satici/urun/duzenle/{id}', [SaticiController::class, 'urunDuzenleForm'])->name('urun.duzenle.form');
        Route::put('/satici/urun/duzenle/{id}', [SaticiController::class, 'urunDuzenle'])->name('urun.duzenle');
        Route::delete('/satici/urun/sil/{id}', [SaticiController::class, 'urunSil'])->name('urun.sil');

        Route::get('/bekleyen-siparisler', [SaticiController::class, 'bekleyenSiparisler'])->name('bekleyen-siparisler');
        Route::post('/siparis/durum-guncelle/{id}', [SaticiController::class, 'updateSiparisDurum'])->name('satici.siparis.durum.guncelle');

        Route::get('/satislarim', [SaticiController::class, 'satislarim'])->name('satici.satislarim');
    });
});
