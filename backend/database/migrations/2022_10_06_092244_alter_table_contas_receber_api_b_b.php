<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContasReceberApiBB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->string('apibb_numero')->nullable();
            $table->string('apibb_linha_digitavel')->nullable();
            $table->string('apibb_codigo_barra_numerico')->nullable();
            $table->string('apibb_qrcode_url')->nullable();
            $table->string('apibb_qrcode_tx_id')->nullable();
            $table->text('apibb_qrcode_emv')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->dropColumn('apibb_numero');
            $table->dropColumn('apibb_linha_digitavel');
            $table->dropColumn('apibb_codigo_barra_numerico');
            $table->dropColumn('apibb_qrcode_url');
            $table->dropColumn('apibb_qrcode_tx_id');
            $table->dropColumn('apibb_qrcode_emv');
        });
    }
}
