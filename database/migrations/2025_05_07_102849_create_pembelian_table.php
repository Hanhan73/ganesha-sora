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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('id_pembelian')->unique();
            $table->date('tanggal');
        
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        
            $table->foreignId('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->integer('jumlah_pembelian');
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
        Schema::dropIfExists('pembelian');
    }
};
