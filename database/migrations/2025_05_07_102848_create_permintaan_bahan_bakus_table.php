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
        Schema::create('permintaan_bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('id_permintaan')->unique();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_bahan_baku');
    }
};
