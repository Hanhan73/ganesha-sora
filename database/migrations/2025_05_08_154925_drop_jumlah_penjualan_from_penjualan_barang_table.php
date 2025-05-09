<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
        {
            Schema::table('penjualan_barang', function (Blueprint $table) {
    
                $table->dropColumn('jumlah_penjualan');
            });
        }
        
        public function down(): void
        {
            Schema::table('penjualan_barang', function (Blueprint $table) {
                $table->string('produk_id');
            });
        }
};
