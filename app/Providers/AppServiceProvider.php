<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use App\Models\SiparisDetay;
use App\Models\Urun;
use App\Models\Siparisler;
use App\Models\Yorumlar;
use App\Models\GecmisAlim;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Carbon::setLocale('tr');
        Date::setLocale('tr');
        View::composer('layouts.satici', function ($view) {
            $kullanici = Auth::user();

            // Giriş yapmış kullanıcının tüm ürünlerini al
            $urunler = Urun::where('kullanici_id', $kullanici->id)->get();

            // Bu ürünlerin sipariş detaylarında olup olmadığını kontrol et ve sipariş_id'leri al
            $siparisIds = SiparisDetay::whereIn('urun_id', $urunler->pluck('id'))->distinct('siparis_id')->pluck('siparis_id');

            // Bekleyen sipariş sayısını al
            $bekleyenSiparisSayisi = Siparisler::whereIn('id', $siparisIds)
                ->where('durum', '!=', 'teslim edildi')
                ->count();

            // Teslim edilmiş sipariş sayısını al
            $teslimEdilenSiparisSayisi = Siparisler::whereIn('id', $siparisIds)->where('durum', 'teslim edildi')->count();

            // Kullanıcı adı ve bakiye bilgisini al
            $kullaniciAdi = $kullanici->kullaniciAdi;
            $bakiye = $kullanici->bakiye;

            // Bekleyen ve teslim edilmiş sipariş sayılarını, kullanıcı adı ve bakiyeyi view'e geçir
            $view->with([
                'bekleyenSiparisSayisi' => $bekleyenSiparisSayisi,
                'teslimEdilenSiparisSayisi' => $teslimEdilenSiparisSayisi,
                'kullaniciAdi' => $kullaniciAdi,
                'bakiye' => $bakiye,
            ]);
        });




        View::composer(['layouts.musteri'], function ($view) {
            if (Auth::check()) {
                $kullanici = Auth::user();
                $kullaniciAdi = $kullanici->kullaniciAdi;
                $bakiye = $kullanici->bakiye;
                // Müşteri için sepet ürün sayısı
                $sepetUrunSayisi = $kullanici->sepet->count();
                // Müşteri için siparişlerim sayısı
                $siparislerimSayisi = $kullanici->siparisler()->where('durum', '!=', 'teslim edildi')->count();
            } else {
                $kullaniciAdi = null;
                $bakiye = null;
                $sepetUrunSayisi = 0;
                $siparislerimSayisi = 0;
            }


            $view->with(compact('kullaniciAdi', 'bakiye', 'sepetUrunSayisi', 'siparislerimSayisi'));
        });

    }
}
