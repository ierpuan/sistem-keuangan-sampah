<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pembayaran', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_tagihan');
            $table->unsignedBigInteger('id_pengguna');
            $table->timestamp('tgl_bayar')->useCurrent();
            $table->decimal('jml_bayar_input', 10, 2);

            // Foreign keys
            $table->foreign('id_tagihan')
                  ->references('id_tagihan')
                  ->on('tagihan')
                  ->onDelete('restrict');

            $table->foreign('id_pengguna')
                  ->references('id_pengguna')
                  ->on('pengguna')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayaran');
    }
};