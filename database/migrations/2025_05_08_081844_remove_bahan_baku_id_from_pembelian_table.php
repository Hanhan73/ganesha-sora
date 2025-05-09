<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pembelian', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['bahan_baku_id']);
            
            // Baru hapus kolom
            $table->dropColumn('bahan_baku_id');
        });
    }

    public function down()
    {
        Schema::table('pembelian', function (Blueprint $table) {
            // Tambahkan kembali kolom jika rollback
            $table->unsignedBigInteger('bahan_baku_id')->nullable();

            // Tambahkan kembali foreign key
            $table->foreign('bahan_baku_id')->references('id_bahan_baku')->on('bahan_baku');
        });
    }
};

