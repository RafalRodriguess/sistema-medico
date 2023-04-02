<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIncCampoConfigFiscal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracoes_fiscais', function (Blueprint $table) {
            $table->string('item_lista_servicos')->nullable();
            $table->string('usuario')->nullable();
            $table->string('senha')->nullable();
            $table->string('certificado')->nullable();
            $table->string('senha_certificado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracoes_fiscais', function (Blueprint $table) {
            //
        });
    }
}
