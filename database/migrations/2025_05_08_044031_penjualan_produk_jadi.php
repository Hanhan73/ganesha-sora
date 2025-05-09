<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan_produk_jadi', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_penjualan');
            $table->decimal('harga', 12, 2);
            $table->timestamps();
        
            $table->foreignId('id_penjualan')->constrained('penjualan_barang')->onDelete('cascade');
            $table->foreignId('id_produk_jadi')->constrained('produk_jadi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
