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
class MusteriController extends Controller
{
    public function anasayfa()
    {
        $kullanici = Auth::user();
        $kullaniciAdi = $kullanici->kullaniciAdi;
        $bakiye = $kullanici->bakiye;

        // Kategorileri getir
        $kategoriler = Kategori::all();

        // Tüm ürünleri getir
        $urunler = Urun::all();

        return view('musteri.anasayfa', compact('kullaniciAdi', 'bakiye', 'kategoriler', 'urunler'));
    }
    public function kategoriDetay($id)
    {
        // İlgili kategori bilgilerini ve ürünlerini al
        $kategori = Kategori::findOrFail($id);
        $urunler = $kategori->urunler()->get();

        return view('musteri.kategori-detay', compact('kategori', 'urunler'));
    }

    public function urunDetay($id)
    {
        $kullanici = Auth::user();
        $kullaniciAdi = $kullanici->kullaniciAdi;
        $bakiye = $kullanici->bakiye;
        // İlgili ürün bilgilerini al
        $urun = Urun::findOrFail($id);

        return view('musteri.urun-detay', compact('kullaniciAdi', 'bakiye','urun'));
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

        return view('musteri.sepet', compact('kullaniciAdi', 'bakiye', 'kategoriler', 'urunler', 'sepet', 'toplamTutar'));
    }


    public function sepeteEkle(Request $request, $urunId)
    {
        $request->validate([
            'miktar' => 'required|integer|min:1',
        ]);

        // Ürünü al ve ilgili satıcıyı kontrol et
        $urun = Urun::findOrFail($urunId);
        $satici_id = $urun->kullanici_id;

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

                // Ürün stok miktarını azalt
                $urun->stok -= $item->miktar;
                $urun->save();
            }

            // Kullanıcının bakiyesi yeterli mi kontrol et
            if ($kullanici->bakiye < $toplamTutar) {
                return redirect()->route('musteri.anasayfa')->with('error', 'Bakiyeniz yetersiz olduğu için sipariş oluşturulamadı!');
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
        $siparisler = $kullanici->siparisler()->with('siparisDetaylari')->get();

        // Siparişleri siparislerim.blade.php dosyasına gönderin
        return view('musteri.siparislerim', compact('siparisler', 'kullaniciAdi', 'bakiye',));
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


        return view('musteri.ayarlar', compact('kullaniciAdi', 'bakiye'));
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

}
