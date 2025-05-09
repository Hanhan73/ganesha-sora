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
        Schema::create('penjualan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('id_penjualan_barang')->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');

            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk_jadi')->onDelete('cascade');
            
            $table->integer('jumlah_penjualan');
            $table->integer('total');
            $table->string('status_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_barang');
    }
};
