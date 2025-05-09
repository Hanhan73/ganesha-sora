<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProdukJadi;

class ProdukJadiSeeder extends Seeder
{
    public function run()
    {
        ProdukJadi::insert([
            ['id_produk_jadi' => 'PJ-0001', 'produk' => 'Keju Mozarella', 'harga' => 50000],
            ['id_produk_jadi' => 'PJ-0002', 'produk' => 'Mentega Premium', 'harga' => 35000],
        ]);
    }
}

