<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan_barang', function (Blueprint $table) {

            $table->dropForeign(['produk_id']);
            $table->dropColumn('produk_id');
        });
    }
    
    public function down(): void
    {
        Schema::table('penjualan_barang', function (Blueprint $table) {
            $table->string('produk_id');
        });
    }
};
