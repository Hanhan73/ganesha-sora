<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        Pelanggan::insert([
            ['id_pelanggan' => 'PLG-0001', 'pelanggan' => 'Toko Roti Enak', 'alamat' => 'Jl. Sersan Bajuri No.1', 'no_telepon' => '081234567890'],
            ['id_pelanggan' => 'PLG-0002', 'pelanggan' => 'Café Lembang', 'alamat' => 'Jl. Lembang Indah', 'no_telepon' => '082134567891'],
        ]);
    }
}