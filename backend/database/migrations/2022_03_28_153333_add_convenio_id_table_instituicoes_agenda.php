<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConvenioIdTableInstituicoesAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_agenda_has_convenio', function (Blueprint $table) {
            $table->foreignId('convenio_id')->references('id')->on('convenios')->index('convenio_id_has_intituicao_agenda');
            $table->foreignId('instituicao_agenda_id')->references('id')->on('instituicoes_agenda')->index('instituicao_agenda_id_has_convenio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicao_agenda_has_convenio');
    }
}
