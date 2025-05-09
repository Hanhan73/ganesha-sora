<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barang';
    protected $fillable = ['barang', 'jenis_barang', 'stok', 'id_barang'];
}
