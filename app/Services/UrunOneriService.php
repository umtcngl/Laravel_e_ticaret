<?php

namespace App\Services;

use App\Models\KullaniciAktivite;
use App\Models\UrunOneri;
use App\Models\Favori;
use App\Models\Urun;
use App\Models\DigerAktiviteler;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrunOneriService
{
    public function updateRecommendations()
    {
        // Sayfa URL'sinden kategori ID'sini ayırma işlevi
        function extractCategoryIdFromUrl($url) {
            // URL'yi '/' karakterlerine göre ayır
            $parts = explode('/', $url);

            // Son elemanı al (muhtemelen kategori ID'sidir)
            $lastPart = end($parts);

            // Eğer son eleman bir sayıysa, bu kategori ID'sidir
            if (is_numeric($lastPart)) {
                return intval($lastPart); // Sayıya çevirip döndür
            } else {
                return null; // Eğer bir sayı değilse, kategori ID'si yok demektir
            }
        }
        // Önceki önerileri sil
        UrunOneri::truncate();

        // Ürün ID'leri, Kategori ID'leri ve Skorları saklayacak diziler oluşturun
        $urunler = [];
        $kategoriIdler = [];
        $urunSkorlari = [];

        $encokziyaretedilenurun_idleri=[];
        $encokziyaretedilenkategori_idleri=[];
        $urunSureleri = [];
        $KategoriSureleri=[];
        // Son bir hafta içindeki tarih
        $haftaOnce = Carbon::now()->subWeek();

        // Favoride olmayan tüm ürünleri al
        $favorideOlmayanlar = Urun::whereNotIn('id', function ($query) {
            $query->select('urun_id')->from('favoriler');
        })->pluck('id');

        foreach ($favorideOlmayanlar as $urunId) {
            $urunler[$urunId] = $urunId;
            $kategoriId = Urun::find($urunId)->kategori_id;
            $kategoriIdler[$urunId] = $kategoriId;
            $urunSkorlari[$urunId] = 0;
        }

        // Son bir hafta içindeki aktiviteleri al
        $aktifKullaniciIDs = KullaniciAktivite::where('created_at', '>=', $haftaOnce)->distinct()->pluck('kullanici_id');

        foreach ($aktifKullaniciIDs as $kullaniciID) {
            // Kullanıcının son bir hafta içindeki aktivitelerini al
            $aktiviteler = KullaniciAktivite::where('kullanici_id', $kullaniciID)
                ->where('created_at', '>=', $haftaOnce)
                ->get();

            foreach ($aktiviteler as $aktivite) {
                if ($aktivite->urun_id !== null && isset($urunler[$aktivite->urun_id])) {
                    // Ürünlerle ilgili aktiviteler
                    $urunId = $aktivite->urun_id;
                    // Ürünün süresini toplama ekle
                    if (!isset($urunSureleri[$urunId])) {
                        $urunSureleri[$urunId] = 0;
                    }
                    $urunSureleri[$urunId] += $aktivite->sure_saniye;
                }elseif ($kategoriId = extractCategoryIdFromUrl($aktivite->sayfa_url)) {
                    // Eğer urun_id yoksa ve kategori_id varsa
                    // Kategoriye ait olan ürünleri bul
                    $kategoriyeAitUrunler = Urun::where('kategori_id', $kategoriId)->pluck('id');

                    // Kategoriye ait olan ürünlerin skorlarını güncelle
                    foreach ($kategoriyeAitUrunler as $kategoriyeAitUrunId) {
                        if (isset($urunler[$kategoriyeAitUrunId])) {
                            // Ürünün süresini toplama ekle
                            if (!isset($KategoriSureleri[$kategoriyeAitUrunId])) {
                                $KategoriSureleri[$kategoriyeAitUrunId] = 0;
                            }
                            $KategoriSureleri[$kategoriyeAitUrunId] += $aktivite->sure_saniye;
                        }
                    }
                }
            }
        }
        // Ürün sürelerini sırala
        arsort($urunSureleri);

        // En yüksek üç süreyi seç
        $enYuksekUrunSureleri = array_slice($urunSureleri, 0, 3, true);
        foreach ($enYuksekUrunSureleri as $urunId => $sure) {
            // Ürün skorlarını güncelle
            if (isset($urunSkorlari[$urunId])) {
                if ($urunSkorlari[$urunId] == 0) {
                    $urunSkorlari[$urunId] += 3; // En yüksek ürün için +3 puan
                } elseif ($urunSkorlari[$urunId] == 1) {
                    $urunSkorlari[$urunId] += 2; // İkinci en yüksek ürün için +2 puan
                } elseif ($urunSkorlari[$urunId] == 2) {
                    $urunSkorlari[$urunId] += 1; // Üçüncü en yüksek ürün için +1 puan
                }
            }
        }


        // Kategori sürelerini sırala
        arsort($KategoriSureleri);

        // En yüksek üç kategori süresini seç
        $enYuksekKategoriSureleri = array_slice($KategoriSureleri, 0, 3, true);
        foreach ($enYuksekKategoriSureleri as $urunId => $sure) {
            // Kategoriye ait olan ürünlerin skorlarını güncelle
            $kategoriId = Urun::find($urunId)->kategori_id;
            $kategoriyeAitUrunler = Urun::where('kategori_id', $kategoriId)->pluck('id');
            foreach ($kategoriyeAitUrunler as $kategoriyeAitUrunId) {
                if (isset($urunSkorlari[$kategoriyeAitUrunId])) {
                    if ($urunSkorlari[$kategoriyeAitUrunId] == 0) {
                        $urunSkorlari[$kategoriyeAitUrunId] += 3; // En yüksek kategori için +3 puan
                    } elseif ($urunSkorlari[$kategoriyeAitUrunId] == 1) {
                        $urunSkorlari[$kategoriyeAitUrunId] += 2; // İkinci en yüksek kategori için +2 puan
                    } elseif ($urunSkorlari[$kategoriyeAitUrunId] == 2) {
                        $urunSkorlari[$kategoriyeAitUrunId] += 1; // Üçüncü en yüksek kategori için +1 puan
                    }
                }
            }
        }



        // 'Arama yapıldı' işlemlerini sayacak dizi oluştur
        $aramaSayilari = [];

        // 'Arama yapıldı' işlemine sahip olan urun_id'leri say
        $aramaAktifKullaniciIDs = DigerAktiviteler::where('created_at', '>=', $haftaOnce)
            ->where('islem', 'Arama yapıldı')
            ->distinct()
            ->pluck('urun_id');

        foreach ($aramaAktifKullaniciIDs as $urunID) {
            if (!isset($aramaSayilari[$urunID])) {
                $aramaSayilari[$urunID] = 0;
            }
            $aramaSayilari[$urunID]++;
        }

        $enCokArananUrunID = null;

        if (!empty($aramaSayilari)) {
            $enCokArananUrunID = array_search(max($aramaSayilari), $aramaSayilari);
        }


        // 'Sepete Ürün Ekledi' işlemlerini sayacak dizi oluştur
        $sepetSayilari = [];

        // 'Sepete Ürün Ekledi' işlemine sahip olan urun_id'leri say
        $sepetAktifKullaniciIDs = DigerAktiviteler::where('created_at', '>=', $haftaOnce)
            ->where('islem', 'Sepete Ürün Ekledi')
            ->distinct()
            ->pluck('urun_id');

        foreach ($sepetAktifKullaniciIDs as $urunID) {
            if (!isset($sepetSayilari[$urunID])) {
                $sepetSayilari[$urunID] = 0;
            }
            $sepetSayilari[$urunID]++;
        }

        // 'Arama yapıldı' işlemine göre puan ekle
        foreach ($aramaSayilari as $urunID => $aramaSayisi) {
            if ($urunID == $enCokArananUrunID && isset($urunSkorlari[$urunID])) {
                $urunSkorlari[$urunID] += 2;
            }
        }

        foreach ($sepetSayilari as $urunID => $sepetSayisi) {
            if (isset($urunSkorlari[$urunID])) {
                $urunSkorlari[$urunID] += 1;
            } else {
                // Eğer anahtar mevcut değilse, uygun bir işlem yapabilirsiniz
            }
        }


        // Ürün skorlarını sırala
        arsort($urunSkorlari);

        // En yüksek üç skoru seç
        $enYuksekUrunSkorlari = array_slice($urunSkorlari, 0, 3, true);

        // En yüksek skorları olan ürünleri öneri listesine ekle
        foreach ($enYuksekUrunSkorlari as $urunId => $skor) {

            if ($skor < 2) {
                continue;
            }

            UrunOneri::create([
                'kullanici_id' => $kullaniciID,
                'urun_id' => $urunId,
                'oneri_tarihi' => now(),
            ]);
        }

        // Son bir haftadan eski aktiviteleri sil
        KullaniciAktivite::where('created_at', '<', $haftaOnce)->delete();
        DigerAktiviteler::where('created_at', '<', $haftaOnce)->delete();
    }
}
