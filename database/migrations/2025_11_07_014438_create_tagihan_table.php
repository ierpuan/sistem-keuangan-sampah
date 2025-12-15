<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id('id_tagihan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->string('periode', 7); // Format: YYYY-MM
            $table->decimal('jml_tagihan_pokok', 10, 2);
            $table->date('jatuh_tempo');
            $table->decimal('total_sudah_bayar', 10, 2)->default(0);
            $table->enum('status', ['Belum Bayar', 'Tunggakan', 'Lunas'])->default('Belum Bayar');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign key
            $table->foreign('id_pelanggan')
                  ->references('id_pelanggan')
                  ->on('pelanggan')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique(['id_pelanggan', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};