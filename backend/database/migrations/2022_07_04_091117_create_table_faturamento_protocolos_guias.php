<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFaturamentoProtocolosGuias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturamento_protocolos_guias', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_externo')->index()->comment("codigo no sistema de terceiro")->nullable();
            $table->tinyInteger('status')->comment("0 - criado, 1 - finalizado, 2 - pendencia")->default(0);
            $table->foreignId('faturamento_protocolo_id')->references('id')->on('faturamento_protocolos');
            $table->foreignId('agendamento_id')->references('id')->on('agendamentos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faturamento_protocolos_guias');
    }
}
