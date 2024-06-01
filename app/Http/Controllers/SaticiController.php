<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;
use App\Models\Kategori;
use App\Models\Siparisler;
use App\Models\SiparisDetay;
use Illuminate\Support\Facades\Auth;

class SaticiController extends Controller
{
    public function anasayfa()
    {
        return view('satici.anasayfa');
    }

    public function urunlerim()
    {
        $urunler = Urun::where('kullanici_id', Auth::id())->get();
        return view('satici.urunlerim', compact('urunler'));
    }

    public function urunEkleForm()
    {
        $kategoriler = Kategori::all();
        return view('satici.urunekle', compact('kategoriler'));
    }

    public function urunEkle(Request $request)
    {
        $request->validate([
            'urunAdi' => 'required',
            'aciklama' => 'required',
            'fiyat' => 'required|numeric',
            'stok' => 'required|numeric',
            'kategori_id' => 'required', // Kategori seçimi zorunlu
            'resim' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Resim doğrulama kuralları
        ]);

        if ($request->hasFile('resim')) {
            $image = $request->file('resim');
            if ($image->isValid()) {
                $file_name = time() . '.' . $image->getClientOriginalExtension();
                if ($image->move(public_path('images'), $file_name)) {
                    $resim_yolu = 'images/' . $file_name;
                } else {
                    return back()->withInput()->withErrors(['resim' => 'Resim yükleme işlemi başarısız.']);
                }
            } else {
                return back()->withInput()->withErrors(['resim' => 'Geçersiz dosya.']);
            }
        } else {
            $resim_yolu = null; // Eğer resim yüklenmediyse null olarak ayarla
        }

        $urun = new Urun();
        $urun->urunAdi = $request->urunAdi;
        $urun->aciklama = $request->aciklama;
        $urun->fiyat = $request->fiyat;
        $urun->stok = $request->stok;
        $urun->kategori_id = $request->kategori_id; // Kategori ID'sini atama
        $urun->kullanici_id = Auth::id();
        $urun->resim_yolu = $resim_yolu; // Resim yolu atama
        $urun->save();

        return redirect()->route('urunlerim')->with('success', 'Ürün başarıyla eklendi.');
    }

    public function urunDuzenleForm($id)
    {
        $urun = Urun::findOrFail($id);
        $kategoriler = Kategori::all();
        return view('satici.urunduzenle', compact('urun', 'kategoriler'));
    }


    public function urunDuzenle(Request $request, $id)
{
    $request->validate([
        'urunAdi' => 'required',
        'aciklama' => 'required',
        'fiyat' => 'required|numeric',
        'stok' => 'required|numeric',
        'kategori_id' => 'required', // Kategori seçme zorunluluğu
    ]);

    $urun = Urun::findOrFail($id);
    $urun->urunAdi = $request->input('urunAdi');
    $urun->aciklama = $request->input('aciklama');
    $urun->fiyat = $request->input('fiyat');
    $urun->stok = $request->input('stok');
    $urun->kategori_id = $request->input('kategori_id'); // Kategori bilgisini güncelle

    // Resmin var olup olmadığını kontrol et
    if ($request->hasFile('resim')) {
        // Eğer ürünün mevcut bir resmi varsa, onu sil
        if ($urun->resim_yolu) {
            $resim_yolu = public_path($urun->resim_yolu);
            if (file_exists($resim_yolu)) {
                unlink($resim_yolu);
            }
        }

        // Yeni resmi yükle
        $image = $request->file('resim');
        if ($image->isValid()) {
            $file_name = time() . '.' . $image->getClientOriginalExtension();
            if ($image->move(public_path('images'), $file_name)) {
                $resim_yolu = 'images/' . $file_name;
            } else {
                return back()->withInput()->withErrors(['resim' => 'Resim yükleme işlemi başarısız.']);
            }
        } else {
            return back()->withInput()->withErrors(['resim' => 'Geçersiz dosya.']);
        }

        // Yeni resim yolu bilgisini güncelle
        $urun->resim_yolu = $resim_yolu;
    }

    $urun->save();

    return redirect()->route('urunlerim')->with('success', 'Ürün başarıyla güncellendi.');
}

public function urunSil($id)
{
    $urun = Urun::findOrFail($id);

    // Eğer ürünün resmi varsa, onu sil
    if ($urun->resim_yolu) {
        $resim_yolu = public_path($urun->resim_yolu);
        if (file_exists($resim_yolu)) {
            unlink($resim_yolu);
        }
    }

    $urun->delete();

    return redirect()->route('urunlerim')->with('success', 'Ürün başarıyla silindi.');
}
public function bekleyenSiparisler()
{
    // Bekleyen siparişleri al
    $bekleyenSiparisler = Siparisler::get();

    // Bekleyen siparişleri gösteren bir view döndür
    return view('satici.bekleyen-siparisler', compact('bekleyenSiparisler'));
}
public function updateSiparisDurum(Request $request, $id)
{
    // Siparişin durumunu güncelle
    $siparis = Siparisler::findOrFail($id);
    $siparis->durum = $request->durum;
    $siparis->save();

    return redirect()->back()->with('success', 'Sipariş durumu güncellendi.');
}
}
