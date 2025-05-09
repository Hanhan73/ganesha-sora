<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $fillable = ['pelanggan', 'alamat', 'no_telepon', 'id_pelanggan'];

    public function penjualanBarang() {
        return $this->hasMany(PenjualanBarang::class, 'id_pelanggan');
    }
}

