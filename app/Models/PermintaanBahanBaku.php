<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanBahanBaku extends Model
{
    protected $table = 'permintaan_bahan_baku';
    protected $fillable = ['id_permintaan', 'user_id', 'tanggal'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bahanBaku()
    {
        return $this->belongsToMany(BahanBaku::class, 'bahan_baku_permintaan', 'permintaan_id', 'bahan_baku_id')
                    ->withPivot('jumlah_permintaan')
                    ->withTimestamps();
    }
}

