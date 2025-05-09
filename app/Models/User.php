<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'user_id';

    protected $fillable = ['username', 'password', 'role'];

    public function pembelian() {
        return $this->hasMany(Pembelian::class, 'user_id');
    }

    public function permintaanBahanBaku() {
        return $this->hasMany(PermintaanBahanBaku::class, 'user_id');
    }

    public function produksi() {
        return $this->hasMany(Produksi::class, 'user_id');
    }

    public function penjualanBarang() {
        return $this->hasMany(PenjualanBarang::class, 'user_id');
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        
        return $this->role === $role;
    }
}
