<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BahanBaku;

class BahanBakuSeeder extends Seeder
{
    public function run()
    {
        BahanBaku::insert([
            ['id_bahan_baku' => 'BB-0001', 'bahan_baku' => 'Susu Sapi Segar'],
            ['id_bahan_baku' => 'BB-0002', 'bahan_baku' => 'Garam'],
            ['id_bahan_baku' => 'BB-0003', 'bahan_baku' => 'Rennet'],
        ]);
    }
}
