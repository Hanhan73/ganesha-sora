<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $fillable = ['tanggal', 'user_id', 'supplier_id', 'total', 'status_pembayaran', 'id_pembelian'];

    public function suppliers() {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function bahanBaku()
    {
        return $this->belongsToMany(BahanBaku::class, 'bahan_baku_pembelian')
                    ->withPivot('jumlah_pembelian', 'harga')
                    ->withTimestamps();
    }
    

    public function users() {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
