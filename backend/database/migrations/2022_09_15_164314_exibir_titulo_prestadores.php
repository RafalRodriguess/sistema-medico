<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExibirTituloPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->tinyInteger('exibir_titulo_paciente')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->dropColumn('exibir_titulo_paciente');
        });
    }
}
