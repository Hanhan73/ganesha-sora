<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukJadi extends Model
{
    protected $table = 'produk_jadi';
    protected $fillable = ['produk', 'harga', 'id_produk_jadi', 'produksi_id'];

    public function produksi() {
        return $this->hasMany(Produksi::class, 'produksi_id');
    }

    public function penjualan()
    {
        return $this->belongsToMany(PenjualanBarang::class, 'penjualan_produk_jadi', 'id_produk_jadi', 'id_penjualan_barang')
                    ->withPivot('jumlah_penjualan', 'harga')
                    ->withTimestamps();
    }
}

