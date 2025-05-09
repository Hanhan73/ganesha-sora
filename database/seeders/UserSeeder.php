<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin001',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'gudang001',
            'password' => Hash::make('password123'),
            'role' => 'gudang',
        ]);

        User::create([
            'username' => 'produksi001',
            'password' => Hash::make('password123'),
            'role' => 'produksi',
        ]);

        User::create([
            'username' => 'direktur001',
            'password' => Hash::make('password123'),
            'role' => 'direktur',
        ]);
    }
}