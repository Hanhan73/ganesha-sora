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
        Schema::create('stok_barang', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang')->unique();
            $table->string('barang');
            $table->enum('jenis_barang', ['bahan_baku', 'produk_jadi']);
            $table->integer('stok');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barang');
    }
};
