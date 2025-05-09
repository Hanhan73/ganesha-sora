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
        Schema::create('bahan_baku_produksi', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_bahan_baku');
            $table->timestamps();
            $table->foreignId('produksi_id')->contrained('produksi')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->contrained('bahan_baku')->onDelete('cascade');
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
