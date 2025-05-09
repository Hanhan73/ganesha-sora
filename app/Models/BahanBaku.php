<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $fillable = ['bahan_baku', 'id_bahan_baku'];

    public function produksi()
    {
        return $this->belongsToMany(Produksi::class, 'bahan_baku_produksi', 'id_bahan_baku', 'id_produksi')
                    ->withPivot('jumlah_bahan_baku')
                    ->withTimestamps();
    }

    public function pembelians()
    {
        return $this->belongsToMany(Pembelian::class, 'bahan_baku_pembelian')
                    ->withPivot('jumlah_pembelian', 'harga')
                    ->withTimestamps();
    }
    
    public function permintaan()
    {
        return $this->belongsToMany(PermintaanBahanBaku::class, 'bahan_baku_permintaan', 'bahan_baku_id', 'permintaan_id')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }

}
