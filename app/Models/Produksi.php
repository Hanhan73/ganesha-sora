<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';
    protected $fillable = ['tanggal', 'user_id', 'produk_id', 'jumlah_produksi', 'id_produksi'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produkJadi() {
        return $this->belongsTo(ProdukJadi::class, 'produk_id');
    }
    public function stokBarang() {
        return $this->belongsTo(StokBarang::class, 'produk_id');
    }

    public function bahanBaku()
    {
        return $this->belongsToMany(BahanBaku::class, 'bahan_baku_produksi', 'produksi_id', 'bahan_baku_id')
        ->withPivot('jumlah_bahan_baku')
        ->withTimestamps();
    }
}

