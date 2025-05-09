<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StokBarang;


class StokBarangSeeder extends Seeder
{
    public function run()
    {
        StokBarang::insert([
            [
                'id_barang' => 'BB-0001',
                'barang' => 'Susu Sapi Segar',
                'jenis_barang' => 'bahan_baku',
                'stok' => 100
            ],
            [
                'id_barang' => 'BB-0002',
                'barang' => 'Garam',
                'jenis_barang' => 'bahan_baku',
                'stok' => 50
            ],
        ]);

        StokBarang::insert([
            [
                'id_barang' => 'PJ-0001',
                'barang' => 'Keju Mozarella',
                'jenis_barang' => 'produk_jadi',
                'stok' => 30
            ],
            [
                'id_barang' => 'PJ-0002',
                'barang' => 'Mentega Premium',
                'jenis_barang' => 'produk_jadi',
                'stok' => 20
            ],
        ]);
    }
}

