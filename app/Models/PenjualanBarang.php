<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanBarang extends Model
{
    protected $table = 'penjualan_barang';
    protected $fillable = ['tanggal', 'user_id', 'pelanggan_id', 'total','status_pembayaran', 'id_penjualan', 'id_penjualan_barang'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelanggan() {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    public function produk()
    {
        return $this->belongsToMany(ProdukJadi::class, 'penjualan_produk_jadi', 'id_penjualan', 'id_produk_jadi')
                    ->withPivot('jumlah_penjualan', 'harga')
                    ->withTimestamps();
    }
}

