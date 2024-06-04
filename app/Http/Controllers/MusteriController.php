<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori;
use App\Models\Urun;
use App\Models\Sepet;
use App\Models\Siparisler;
use App\Models\Kullanici;
use App\Models\Yorumlar;
USE App\Models\GecmisAlim;
use App\Models\Favori;
use App\Models\KullaniciAktivite;
use App\Models\DigerAktiviteler;
use App\Models\UrunOneri;
class MusteriController extends Controller
{
    public function anasayfa()
    {
        $onerilenler = UrunOneri::with('urun')->get();
        // Kategorileri getir
        $kategoriler = Kategori::all();

        // Stoğu sıfır olmayan ürünleri getir
        $urunler = Urun::where('stok', '>', 0)->get();

        // Her bir ürün için en yüksek puanı alanları getir
        $enYuksekPuanAlanlar = Yorumlar::select('urun_id', \DB::raw('AVG(puan) as ortalama_puan, COUNT(*) as yorum_sayisi'))
        ->groupBy('urun_id')
        ->orderByRaw('AVG(puan) DESC')
        ->take(4)
        ->get();

    // En çok satan ürünleri getir
    $enCokSatanlar = GecmisAlim::select('urun_id', \DB::raw('COUNT(*) as alim_sayisi'))
        ->groupBy('urun_id')
        ->orderByRaw('COUNT(*) DESC')
        ->take(4)
        ->get();

    // Her bir ürün için puanları ve sipariş edilme sayısını hesapla
    $urunPuanlar = [];
    $urunSiparisSayilari = [];
    foreach ($urunler as $urun) {
        // Ürünün aldığı puanları hesapla
        $puanlar = $urun->yorumlar->pluck('puan')->toArray();
        $toplamPuan = count($puanlar) > 0 ? array_sum($puanlar) : 0;
        $ortalamaPuan = count($puanlar) > 0 ? $toplamPuan / count($puanlar) : 0;
        $urunPuanlar[$urun->id] = $ortalamaPuan;

        // Ürünün sipariş edilme sayısını hesapla
        $urunSiparisSayilari[$urun->id] = GecmisAlim::where('urun_id', $urun->id)->count();
    }

    return view('musteri.anasayfa', compact('kategoriler', 'urunler', 'enYuksekPuanAlanlar', 'enCokSatanlar', 'urunPuanlar', 'urunSiparisSayilari','onerilenler'));
    }

    public function kategoriDetay($id)
    {
        // İlgili kategori bilgilerini ve ürünlerini al
        $kategori = Kategori::findOrFail($id);
        $urunler = $kategori->urunler()->where('stok', '>', 0)->get();


        // Her bir ürün için en yüksek puanı alanları getir
        $enYuksekPuanAlanlar = Yorumlar::select('urun_id', \DB::raw('AVG(puan) as ortalama_puan, COUNT(*) as yorum_sayisi'))
        ->groupBy('urun_id')
        ->orderByRaw('AVG(puan) DESC')
        ->take(4)
        ->get();

        // En çok satan ürünleri getir
        $enCokSatanlar = GecmisAlim::select('urun_id', \DB::raw('COUNT(*) as alim_sayisi'))
            ->groupBy('urun_id')
            ->orderByRaw('COUNT(*) DESC')
            ->take(4)
            ->get();

        // Her bir ürün için puanları ve sipariş edilme sayısını hesapla
        $urunPuanlar = [];
        $urunSiparisSayilari = [];
        foreach ($urunler as $urun) {
            // Ürünün aldığı puanları hesapla
            $puanlar = $urun->yorumlar->pluck('puan')->toArray();
            $toplamPuan = count($puanlar) > 0 ? array_sum($puanlar) : 0;
            $ortalamaPuan = count($puanlar) > 0 ? $toplamPuan / count($puanlar) : 0;
            $urunPuanlar[$urun->id] = $ortalamaPuan;

            // Ürünün sipariş edilme sayısını hesapla
            $urunSiparisSayilari[$urun->id] = GecmisAlim::where('urun_id', $urun->id)->count();
        }

        return view('musteri.kategori-detay', compact('kategori', 'urunler', 'enYuksekPuanAlanlar', 'enCokSatanlar', 'urunPuanlar', 'urunSiparisSayilari'));
    }

    public function urunDetay($id)
    {
        // Kullanıcı bilgisini al
        $kullanici = Auth::user();

        // Kullanıcının adını ve bakiyesini al
        $kullaniciAdi = $kullanici->kullaniciAdi;
        $bakiye = $kullanici->bakiye;

        // İlgili ürün bilgilerini al
        $urun = Urun::findOrFail($id);

        // Kullanıcının daha önce yorum yapmış veya derecelendirme yapmış olup olmadığını kontrol et
        $yapilmisYorum = $urun->yorumlar()->where('kullanici_id', auth()->id())->exists();

        // Kullanıcının daha önce belirli bir ürünü satın alıp almadığını kontrol et
        $dahaOnceAlmisMi = GecmisAlim::where('kullanici_id', auth()->id())->where('urun_id', $id)->exists();

        // Ürünün yorumlarını al ve kullanıcı bulunamazsa "kullanıcı silinmiş" olarak ayarla
        $yorumlar = $urun->yorumlar->map(function($yorum) {
            $kullanici = Kullanici::find($yorum->kullanici_id);
            if (!$kullanici) {
                $yorum->kullaniciAdi = 'Kullanıcı Silinmiş!!';
            } else {
                $yorum->kullaniciAdi = $kullanici->kullaniciAdi;
            }
            return $yorum;
        });

        //-------------------------------------------------------------------------------
        // Her bir ürün için en yüksek puanı alanları getir
        $enYuksekPuanAlanlar = Yorumlar::select('urun_id', \DB::raw('AVG(puan) as ortalama_puan, COUNT(*) as yorum_sayisi'))
        ->groupBy('urun_id')
        ->orderByRaw('AVG(puan) DESC')
        ->take(4)
        ->get();

        // En çok satan ürünleri getir
        $enCokSatanlar = GecmisAlim::select('urun_id', \DB::raw('COUNT(*) as alim_sayisi'))
            ->groupBy('urun_id')
            ->orderByRaw('COUNT(*) DESC')
            ->take(4)
            ->get();

        // Her bir ürün için puanları ve sipariş edilme sayısını hesapla
        $urunPuanlar = [];
        $urunSiparisSayilari = [];

        // Ürün varsa ve yorumları varsa işlemleri yap
        if ($urun && $urun->yorumlar()->exists()) {
            $puanlar = $urun->yorumlar->pluck('puan')->toArray();
            $toplamPuan = count($puanlar) > 0 ? array_sum($puanlar) : 0;
            $ortalamaPuan = count($puanlar) > 0 ? $toplamPuan / count($puanlar) : 0;
            $urunPuanlar[$urun->id] = $ortalamaPuan;
        } else {
            // Eğer yorum yoksa, ortalama puanı sıfır olarak ayarla
            $urunPuanlar[$urun->id] = 0;
        }

        // Ürünün sipariş edilme sayısını hesapla
        $urunSiparisSayilari[$urun->id] = GecmisAlim::where('urun_id', $urun->id)->count();

        return view('musteri.urun-detay', compact('urun', 'kullanici', 'yorumlar', 'dahaOnceAlmisMi', 'yapilmisYorum', 'enYuksekPuanAlanlar', 'enCokSatanlar', 'urunPuanlar', 'urunSiparisSayilari'));
    }




    public function yorumYap(Request $request, $id)
    {
        $request->validate([
            'icerik' => 'required|string|max:255', // Yorum içeriği doğrulama kuralı
            'puan' => 'required|integer|min:0|max:10', // Puan doğrulama kuralı
        ]);

        // Yorumu kaydet
        $yorum = new Yorumlar();
        $yorum->kullanici_id = auth()->id();
        $yorum->urun_id = $id;
        $yorum->icerik = $request->icerik;
        $yorum->puan = $request->puan;
        $yorum->save();

        return redirect()->back()->with('success', 'Yorum başarıyla eklendi.');
    }


    public function sepet()
    {
        $kullanici = Auth::user();
        $kullaniciAdi = $kullanici->kullaniciAdi;
        $bakiye = $kullanici->bakiye;

        // Kategorileri getir
        $kategoriler = Kategori::all();

        // Tüm ürünleri getir
        $urunler = Urun::all();

        // Tüm sepeti getir
        $sepet = Sepet::all();

        // Toplam tutarı hesapla
        $toplamTutar = 0;
        foreach ($sepet as $item) {
            $toplamTutar += $item->urun->fiyat * $item->miktar;
        }

        return view('musteri.sepet', compact( 'kategoriler', 'urunler', 'sepet', 'toplamTutar'));
    }


    public function sepeteEkle(Request $request, $urunId)
    {
        $request->validate([
            'miktar' => 'required|integer|min:1',
        ]);

        // Ürünü al ve ilgili satıcıyı kontrol et
        $urun = Urun::findOrFail($urunId);
        $satici_id = $urun->kullanici_id;
        if ($request->miktar > $urun->stok) {
            return back()->with('warning', 'Stok miktarını aşan bir miktarı sepete ekleyemezsiniz.');
        }
        // Sepette farklı satıcılara ait ürün varsa uyarı ver
        $farkliSatıcılar = Sepet::where('kullanici_id', Auth::id())
                                ->whereHas('urun', function ($query) use ($satici_id) {
                                    $query->where('kullanici_id', '!=', $satici_id);
                                })
                                ->exists();

        if ($farkliSatıcılar) {
            return back()->with('warning', 'Sepette farklı satıcılara ait ürün bulunamaz.');
        }

        // Sepette aynı satıcıya ait ürün var mı kontrol et
        $sepetUrun = Sepet::where('kullanici_id', Auth::id())
                          ->where('urun_id', $urunId)
                          ->first();

        if ($sepetUrun) {
            // Eğer sepette aynı satıcıya ait bir ürün varsa, miktarını arttır
            $sepetUrun->miktar += $request->miktar;
            $sepetUrun->save();
        } else {
            // Eğer sepette aynı satıcıya ait ürün yoksa, yeni bir sepet öğesi oluştur
            $sepet = new Sepet();
            $sepet->urun_id = $urunId;
            $sepet->kullanici_id = Auth::id();
            $sepet->miktar = $request->miktar;
            $sepet->tarih = now(); // Şu anki zamanı kaydet
            $sepet->save();
        }

        // DigerAktiviteler tablosuna kaydet
        $aktivite = new DigerAktiviteler();
        $aktivite->kullanici_id = Auth::id();
        $aktivite->urun_id = $urunId;
        $aktivite->islem = 'Sepete Ürün Ekledi';
        $aktivite->save();

        return redirect()->route('musteri.anasayfa')->with('success', 'Ürün sepete eklendi.');
    }



public function arttir($id)
{
    $sepet = Sepet::findOrFail($id);
    $sepet->miktar += 1;
    $sepet->save();
    return redirect()->route('sepet');
}

public function eksilt($id)
{
    $sepet = Sepet::findOrFail($id);
    if ($sepet->miktar > 1) {
        $sepet->miktar -= 1;
        $sepet->save();
    } else {
        $sepet->delete();
    }
    return redirect()->route('sepet');
}
public function kaldir($id)
{
    $sepet = Sepet::findOrFail($id);
    $sepet->delete();
    return redirect()->route('sepet');
}

    public function siparisOlustur()
    {
        $kullanici = Auth::user();
        $sepet = $kullanici->sepet;

        // Sepette ürün varsa
        if ($sepet->isNotEmpty()) {
            // Toplam tutarı ve sipariş detaylarını hazırla
            $toplamTutar = 0;
            $siparisDetaylari = [];

            foreach ($sepet as $item) {
                $urun = $item->urun;
                $toplamTutar += $urun->fiyat * $item->miktar;
                $siparisDetaylari[] = [
                    'urun_id' => $urun->id,
                    'miktar' => $item->miktar,
                ];
                // Kullanıcının bakiyesi yeterli mi kontrol et
                if ($kullanici->bakiye < $toplamTutar) {
                    return redirect()->route('sepet')->with('error', 'Bakiyeniz yetersiz olduğu için sipariş oluşturulamadı!');
                }
                // Ürün stok miktarını azalt
                $urun->stok -= $item->miktar;
                $urun->save();
            }

            // Siparişi oluştur
            $siparis = new Siparisler([
                'kullanici_id' => $kullanici->id,
                'siparis_tarihi' => now(),
                'toplam_tutar' => $toplamTutar,
                'durum' => 'beklemede',
            ]);
            $siparis->save();

            // Sipariş detaylarını kaydet
            $siparis->siparisDetaylari()->createMany($siparisDetaylari);

            // Kullanıcının bakiyesinden toplam tutarı düş
            $kullanici->bakiye -= $toplamTutar;
            $kullanici->save();

            // Sepeti temizle
            $sepet->each->delete();

            return redirect()->route('musteri.anasayfa')->with('success', 'Siparişiniz başarıyla oluşturuldu!');
        }

        return redirect()->route('musteri.anasayfa')->with('error', 'Sepetiniz boş olduğu için sipariş oluşturulamadı!');
    }

    public function siparislerim()
    {
        $kullanici = Auth::user();
        $kullaniciAdi = $kullanici->kullaniciAdi;
        $bakiye = $kullanici->bakiye;

        // Kullanıcının siparişlerini ve bu siparişlere ait detayları alın
        $siparisler = $kullanici->siparisler()->with('siparisDetaylari')->where('durum', '!=', 'teslim edildi')->get();

        // Siparişleri siparislerim.blade.php dosyasına gönderin
        return view('musteri.siparislerim', compact('siparisler'));
    }


    public function siparis_sil($id)
    {
        // Siparişi bul
        $siparis = Siparisler::findOrFail($id);

        // Sipariş detaylarını al
        $siparisDetaylar = $siparis->siparisDetaylari;

        // Sipariş detaylarını sil ve ürün miktarlarını stoklara geri yükle
        foreach ($siparisDetaylar as $siparisDetay) {
            // Ürünü bul ve miktarı stoklara geri yükle
            $urun = Urun::findOrFail($siparisDetay->urun_id);
            $urun->stok += $siparisDetay->miktar;
            $urun->save();

            // Sipariş detayını sil
            $siparisDetay->delete();
        }
        $iadeMiktari = $siparis->toplam_tutar * 0.9;
        // Para iadesi yap
        $paraIadesiBasarili = $this->paraIadesiYap($siparis->kullanici_id, $iadeMiktari);

        if ($paraIadesiBasarili) {
            $siparis->delete();
            return redirect()->back()->with('success', 'Sipariş başarıyla silindi ve para iadesi yapıldı.');
        } else {
            // Para iadesi sırasında bir hata oluştuysa kullanıcıya bir hata mesajı gönder
            return redirect()->back()->with('error', 'Sipariş silinirken bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }
    public function paraIadesiYap($kullaniciId, $tutar)
    {
        // Kullanıcıyı bul
        $kullanici = Kullanici::findOrFail($kullaniciId);

        // Kullanıcının bakiyesine iade tutarını ekle
        $kullanici->bakiye += $tutar;
        $kullanici->save();
        return true;
    }
    public function ayarlar()
    {
        $kullanici = Auth::user();

        if (!$kullanici) {
            return redirect()->route('giris')->withErrors('Lütfen oturum açın.');
        }

        $kullaniciAdi = $kullanici->kullaniciAdi;
        $bakiye = $kullanici->bakiye;


        return view('musteri.ayarlar');
    }

    public function ayarlarGuncelle(Request $request)
    {
        // Formdan gelen verileri sadece belirtilen alanlarla sınırla
        $validatedData = $request->only([
            'yeni_kullanici_adi',
            'mevcut_sifre',
            'yeni_sifre',
            'yeni_sifre_tekrar',
        ]);

        // Mevcut kullanıcıyı al
        $kullanici = Auth::user();

        // Mevcut şifrenin doğruluğunu kontrol et
        if (!Hash::check($validatedData['mevcut_sifre'], $kullanici->sifre)) {
            return redirect()->route('ayarlar')->with('error', 'Mevcut şifre yanlış. Lütfen tekrar deneyin.');
        }

        // Yeni şifrelerin karşılaştırılması
        if ($validatedData['yeni_sifre'] !== $validatedData['yeni_sifre_tekrar']) {
            return redirect()->route('ayarlar')->with('error', 'Yeni şifreler eşleşmiyor. Lütfen tekrar deneyin.');
        }

        // Kullanıcı adını güncelle
        $kullanici->kullaniciAdi = $validatedData['yeni_kullanici_adi'];
        $kullanici->save();

        // Yeni şifreyi güncelle
        $kullanici->sifre = Hash::make($validatedData['yeni_sifre']);
        $kullanici->save();

        // Başarılı bir şekilde güncellendiğine dair mesaj ile ana sayfaya yönlendir
        return redirect()->route('musteri.anasayfa')->with('success', 'Ayarlar başarıyla güncellendi.');
    }

    public function searchLive(Request $request)
    {
        $query = $request->input('query');

        $kelimeler = explode(' ', $query);

        $results = Urun::where('stok', '>', 0)
            ->where(function ($queryBuilder) use ($kelimeler) {
                foreach ($kelimeler as $kelime) {
                    if (strlen($kelime) >= 2) { // Karakter sayısı kontrolü
                        $queryBuilder->orWhere('urunAdi', 'LIKE', "%{$kelime}%");
                    }
                }
            })
            ->get();

        return response()->json($results);
    }

    public function aramaSonuclari(Request $request)
    {
        $query = $request->input('query');

        $kelimeler = explode(' ', $query);

        $urunler = Urun::where('stok', '>', 0)
            ->where(function ($queryBuilder) use ($kelimeler) {
                foreach ($kelimeler as $kelime) {
                    if (strlen($kelime) >= 2) { // Karakter sayısı kontrolü
                        $queryBuilder->orWhere('urunAdi', 'LIKE', "%{$kelime}%");
                    }
                }
            })
            ->get();

        // Arama sorgusunu ve bulunan ürünleri kaydetme
        if (Auth::check()) {
            $kullaniciId = Auth::id();

            foreach ($urunler as $urun) {
                DigerAktiviteler::create([
                    'kullanici_id' => $kullaniciId,
                    'urun_id' => $urun->id,
                    'islem' => 'Arama yapıldı: ' . $query
                ]);
            }
        }

        return view('musteri.arama-sonuclari', compact('urunler'));
    }

    public function hesapGecmisi()
    {
        $kullanici = Auth::user();
        $gecmisSiparisler = $kullanici->siparisler()->where('durum', 'teslim edildi')->orderBy('created_at', 'desc')->get();

        return view('musteri.hesap_gecmisi', compact('gecmisSiparisler'));
    }
    public function favoriToggle(Urun $urun)
    {
        $kullaniciId = Auth::id();

        // Kullanıcının favori kaydını kontrol et
        $favori = Favori::where('kullanici_id', $kullaniciId)->where('urun_id', $urun->id)->first();

        if ($favori) {
            // Favori varsa sil
            $favori->delete();
            $message = 'Ürün favorilerden çıkarıldı.';
        } else {
            // Favori yoksa ekle
            Favori::create([
                'kullanici_id' => $kullaniciId,
                'urun_id' => $urun->id,
            ]);
            $message = 'Ürün favorilere eklendi.';
        }

        return redirect()->back()->with('success', $message);
    }
    public function favoriler()
    {
        $favoriler = auth()->user()->favoriler()->with('urun')->get();

        return view('musteri.favoriler', compact('favoriler'));
    }

    public function sayfaSuresiKaydet(Request $request)
    {
        $url = $request->input('url');
        $duration = $request->input('duration');
        $urunId = $request->input('urunId');

        // Kullanıcı aktivitesini kaydet
        $kullaniciAktivite = new KullaniciAktivite();
        $kullaniciAktivite->kullanici_id = auth()->id(); // Oturum açmış kullanıcının ID'sini al
        $kullaniciAktivite->urun_id = $urunId;
        $kullaniciAktivite->sayfa_url = $url;
        $kullaniciAktivite->sure_saniye = $duration;
        $kullaniciAktivite->save();

        return response()->json(['message' => 'Sayfa kalma süresi kaydedildi']);
    }

}
