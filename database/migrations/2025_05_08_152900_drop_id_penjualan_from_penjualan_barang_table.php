<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan_barang', function (Blueprint $table) {
            $table->dropColumn('id_penjualan');
        });
    }
    
    public function down(): void
    {
        Schema::table('penjualan_barang', function (Blueprint $table) {
            $table->string('id_penjualan');
        });
    }
};
