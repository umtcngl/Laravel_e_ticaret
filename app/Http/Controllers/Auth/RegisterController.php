<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kullanici;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.kayit');
    }

    public function register(Request $request)
    {
        // Formdan gelen verileri alma
        $data = $request->validate([
            'kullaniciAdi' => 'required|unique:kullanicilar,kullaniciAdi',
            'sifre' => 'required',
        ]);

        // Kullanıcı oluşturma
        $user = Kullanici::create([
            'kullaniciAdi' => $data['kullaniciAdi'],
            'sifre' => Hash::make($data['sifre']),
            'rol' => 'musteri',
        ]);

        // Başarılı kayıt bildirimi
        return redirect()->route('kayit')->with('success', 'Kayıt başarıyla tamamlandı.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'KullaniciAdi' => ['required', 'string', 'max:255', 'unique:kullanicilar'],
            'Sifre' => ['required', 'string', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return Kullanici::create([
            'KullaniciAdi' => $data['KullaniciAdi'],
            'Sifre' => Hash::make($data['Sifre']),
        ]);
    }
}
