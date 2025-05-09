<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable = ['supplier', 'alamat', 'no_telepon', 'nama_bank', 'no_rekening', 'id_supplier'];

    public function pembelian() {
        return $this->hasMany(Pembelian::class, 'id_supplier');
    }
}
