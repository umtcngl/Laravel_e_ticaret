<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kullanici;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.giris');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('kullaniciAdi', 'sifre');
        $kullanici = Kullanici::where('kullaniciAdi', $credentials['kullaniciAdi'])->first();

        if ($kullanici && $this->validatePassword($credentials['sifre'], $kullanici->sifre)) {
            // Doğrulama başarılı, kullanıcıyı yönlendir
            Auth::login($kullanici);

            // Kullanıcının rolünü kontrol et
            if ($kullanici->rol === 'admin') {
                return redirect()->route('admin.anasayfa');
            } elseif ($kullanici->rol === 'satici') {
                return redirect()->route('satici.anasayfa'); // Satici rolüne özel anasayfaya yönlendir
            }

            // Diğer kullanıcılar için varsayılan ana sayfaya yönlendir
            return redirect()->intended('/anasayfa');
        }

        // Doğrulama başarısız, giriş sayfasına geri yönlendir
        return redirect()->route('giris')->with('error', 'Giriş bilgileri hatalı');
    }


    protected function validatePassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('giris');
    }
}
