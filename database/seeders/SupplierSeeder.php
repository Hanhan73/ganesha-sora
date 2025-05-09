<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        Supplier::insert([
            [
                'id_supplier' => 'SUP-0001',
                'supplier' => 'PT Susu Lembang',
                'alamat' => 'Jl. Lembang No.22',
                'no_telepon' => '081122334455',
                'nama_bank' => 'Bank BCA',
                'no_rekening' => '1234567890'
            ],
        ]);
    }
}
