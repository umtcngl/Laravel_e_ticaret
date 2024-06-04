<?php

namespace App\Services;

use App\Models\KullaniciAktivite;
use App\Models\UrunOneri;
use App\Models\GecmisAlim;
use App\Models\Yorumlar;
use App\Models\Favori;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrunOneriService
{
    public function updateRecommendations()
    {
        UrunOneri::truncate();
        // Son bir hafta içindeki tarih
        $haftaOnce = Carbon::now()->subWeek();

        // Son bir hafta içindeki aktiviteleri al
        $aktifKullaniciIDs = KullaniciAktivite::where('created_at', '>=', $haftaOnce)->distinct()->pluck('kullanici_id');

        foreach ($aktifKullaniciIDs as $kullaniciID) {
            // Kullanıcının son bir hafta içindeki aktivitelerini al
            $aktiviteler = KullaniciAktivite::where('kullanici_id', $kullaniciID)
                ->where('created_at', '>=', $haftaOnce)
                ->get();

            // Kullanıcının en fazla bakılan ürün ve kategorilerini belirle
            $enFazlaBakilanUrun = null;
            $enFazlaBakilanUrunSure = 0;
            $kategoriGezintiSureleri = [];

            foreach ($aktiviteler as $aktivite) {
                if ($aktivite->urun_id !== null) {
                    // Ürünlerle ilgili aktiviteler
                    if (!isset($enFazlaBakilanUrun) || $aktivite->sure_saniye > $enFazlaBakilanUrunSure) {
                        $enFazlaBakilanUrun = $aktivite->urun_id;
                        $enFazlaBakilanUrunSure = $aktivite->sure_saniye;
                    }
                } else {
                    // Kategorilerle ilgili aktiviteler
                    $kategoriId = explode('/', $aktivite->sayfa_url)[1];
                    if (!isset($kategoriGezintiSureleri[$kategoriId])) {
                        $kategoriGezintiSureleri[$kategoriId] = 0;
                    }
                    $kategoriGezintiSureleri[$kategoriId] += $aktivite->sure_saniye;
                }
            }

            // Eğer en fazla bakılan ürün varsa ve süresi 360 saniyeden fazlaysa, öneriye dahil et
            if (isset($enFazlaBakilanUrun) && $enFazlaBakilanUrunSure > 360) {
                // Favoride olup olmadığını kontrol et
                $favorideMi = Favori::where('kullanici_id', $kullaniciID)
                    ->where('urun_id', $enFazlaBakilanUrun)
                    ->exists();

                if (!$favorideMi) {
                    UrunOneri::create([
                        'kullanici_id' => $kullaniciID,
                        'urun_id' => $enFazlaBakilanUrun,
                        'oneri_tarihi' => now(),
                    ]);
                }
            }

            // En fazla gezilen kategoriyi bul
            $enFazlaGezilenKategori = null;
            $enFazlaGezilenSure = 0;
            foreach ($kategoriGezintiSureleri as $kategoriId => $gezintiSure) {
                if ($gezintiSure > $enFazlaGezilenSure) {
                    $enFazlaGezilenKategori = $kategoriId;
                    $enFazlaGezilenSure = $gezintiSure;
                }
            }

            if ($enFazlaGezilenKategori !== null) {
                // Eğer en fazla gezilen kategori varsa
                // Ürün önerisi oluştur
                $onerilecekUrunler = DB::table('urunler')
                    ->join('yorumlar', 'urunler.id', '=', 'yorumlar.urun_id')
                    ->select('urunler.id', DB::raw('AVG(yorumlar.puan) as ortalama_puan'))
                    ->where('urunler.kategori_id', $enFazlaGezilenKategori)
                    ->groupBy('urunler.id')
                    ->orderByDesc('ortalama_puan')
                    ->limit(1) // Önerilecek ürün sayısı
                    ->pluck('urunler.id');

                // Önerilecek ürünleri kaydet
                foreach ($onerilecekUrunler as $urunId) {
                    // Favoride olup olmadığını kontrol et
                    $favorideMi = Favori::where('kullanici_id', $kullaniciID)
                        ->where('urun_id', $urunId)
                        ->exists();

                    if (!$favorideMi) {
                        UrunOneri::create([
                            'kullanici_id' => $kullaniciID,
                            'urun_id' => $urunId,
                            'oneri_tarihi' => now(),
                        ]);
                    }
                }
            }
        }

        // Son bir haftadan eski aktiviteleri sil
        KullaniciAktivite::where('created_at', '<', $haftaOnce)->delete();
    }
}
