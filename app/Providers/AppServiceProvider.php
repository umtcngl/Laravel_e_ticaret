<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\SiparisDetay;
use App\Models\Urun;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('layouts.satici', function ($view) {
            $kullanici = Auth::user();

            // Giriş yapmış kullanıcının tüm ürünlerini al
            $urunler = Urun::where('kullanici_id', $kullanici->id)->get();

            // Bu ürünlerin sipariş detaylarında olup olmadığını kontrol et ve sipariş_id'leri al
            $siparisIds = SiparisDetay::whereIn('urun_id', $urunler->pluck('id'))->distinct('siparis_id')->pluck('siparis_id');

            // Sipariş sayısını al
            $bekleyenSiparisSayisi = $siparisIds->count();

            // Bekleyen sipariş sayısını view'e geçir
            $view->with('bekleyenSiparisSayisi', $bekleyenSiparisSayisi);
        });


        View::composer(['layouts.musteri'], function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                // Müşteri için sepet ürün sayısı
                $sepetUrunSayisi = $user->sepet->count();
                // Müşteri için siparişlerim sayısı
                $SiparislerimSayisi = $user->siparisler->count();
            } else {
                $sepetUrunSayisi = 0;
                $SiparislerimSayisi = 0;
            }
            $view->with(compact('sepetUrunSayisi', 'SiparislerimSayisi'));
        });
    }
}
