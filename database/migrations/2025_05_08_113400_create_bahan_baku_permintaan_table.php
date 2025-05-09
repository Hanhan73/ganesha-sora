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
        Schema::create('bahan_baku_permintaan', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_permintaan');
            $table->timestamps();
            $table->foreignId('permintaan_id')->constrained('permintaan_bahan_baku')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku_permintaan');
    }
};
