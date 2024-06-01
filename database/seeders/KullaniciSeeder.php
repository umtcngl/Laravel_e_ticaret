<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KullaniciSeeder extends Seeder
{
    public function run()
    {
        DB::table('kullanicilar')->insert([
            'kullaniciAdi' => 'Admin',
            'sifre' => Hash::make('admin123'),
            'rol' => 'admin',
        ]);
    }
}
