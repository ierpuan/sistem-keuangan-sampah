<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposit_pelanggan', function (Blueprint $table) {
            $table->id('id_deposit');
            $table->unsignedBigInteger('id_pelanggan')->unique();
            $table->decimal('saldo_deposit', 10, 2)->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign key
            $table->foreign('id_pelanggan')
                  ->references('id_pelanggan')
                  ->on('pelanggan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_pelanggan');
    }
};