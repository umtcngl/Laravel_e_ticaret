<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kullanici;
use App\Models\Kategori; // Kategori modelini ekleyin

class AdminController extends Controller
{
    public function anasayfa()
    {
        return view('admin.anasayfa');
    }

    public function kullanici()
    {
        $kullanicilar = Kullanici::all();
        return view('admin.kullanici', compact('kullanicilar'));
    }

    public function kullaniciRol(Request $request, $id)
    {
        $kullanici = Kullanici::findOrFail($id);
        $kullanici->rol = $request->input('rol');
        $kullanici->save();

        return redirect()->back()->with('success', 'Kullanıcı rolü başarıyla güncellendi.');
    }

    public function kullaniciBakiyeKaydet(Request $request, $id)
    {
        $kullanici = Kullanici::findOrFail($id);
        $kullanici->bakiye = $request->input('bakiye');
        $kullanici->save();

        return redirect()->back()->with('success', 'Kullanıcı bakiyesi başarıyla güncellendi.');
    }

    public function kullaniciSil($id)
    {
        $kullanici = Kullanici::find($id);

        if ($kullanici) {
            $kullanici->delete();
            return redirect()->back()->with('success', 'Kullanıcı silindi.');
        }

        return redirect()->back()->with('error', 'Kullanıcı bulunamadı.');
    }



    public function kategori()
    {
        $kategoriler = Kategori::with('urunler')->get(); // Alt kategorileri de çekiyoruz
        return view('admin.kategori', compact('kategoriler'));
    }

    public function kategoriEkle()
    {
        return view('admin.kategori_ekle');
    }

    public function kategoriKaydet(Request $request)
    {
        $request->validate([
            'kategoriAdi' => 'required|string',
            'aciklama' => 'required|string',
            'icon_yolu' => 'nullable|string',
            'icon' => 'nullable|string',
        ], [
            'icon_yolu.required_without' => 'Icon veya Icon Yolu alanlarından en az birisi doldurulmalıdır.',
            'icon.required_without' => 'Icon veya Icon Yolu alanlarından en az birisi doldurulmalıdır.',
        ]);


        $data = $request->only('kategoriAdi', 'aciklama', 'icon_yolu', 'icon');

        Kategori::create($data);

        return redirect()->route('admin.kategori')->with('success', 'Kategori başarıyla eklendi.');
    }
    public function kategoriSil($id)
    {
        // Kategoriyi bul
        $kategori = Kategori::findOrFail($id);

        // Kategoriye ait ürünleri sil
        $kategori->urunler()->delete();

        // Kategoriyi sil
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori ve bu kategoriye ait tüm ürünler başarıyla silindi.');
    }

    public function duzenle($id)
    {
        // Düzenlenecek kategoriyi bul
        $kategori = Kategori::findOrFail($id);

        // Kategori düzenleme formunu göster
        return view('admin.kategori_duzenle', compact('kategori'));
    }
    public function guncelle(Request $request, $id)
    {
        // İlgili kategoriyi bul
        $kategori = Kategori::findOrFail($id);

        // Kategori bilgilerini güncelle
        $kategori->kategoriAdi = $request->input('kategoriAdi');
        $kategori->aciklama = $request->input('aciklama');
        $kategori->icon_yolu = $request->input('icon_yolu');
        $kategori->icon = $request->input('icon');
        $kategori->save();

        // Başarılı bir şekilde güncellendiğine dair mesajla ana kategori yönetimi sayfasına yönlendir
        return redirect()->route('admin.kategori')->with('success', 'Kategori başarıyla güncellendi.');
    }




}
