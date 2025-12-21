<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi_pembayaran', function (Blueprint $table) {
            $table->decimal('jml_bayar_dari_deposit', 15, 2)->default(0)->after('jml_bayar_input');
        });
    }

    public function down()
    {
        Schema::table('transaksi_pembayaran', function (Blueprint $table) {
            $table->dropColumn('jml_bayar_dari_deposit');
        });
    }
};