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
        Schema::create('produksi', function (Blueprint $table) {
            $table->id();
            $table->string('id_produksi')->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk_jadi')->onDelete('cascade');
            $table->integer('jumlah_produksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
