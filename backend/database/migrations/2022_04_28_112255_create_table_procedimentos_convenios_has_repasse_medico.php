<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProcedimentosConveniosHasRepasseMedico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimentos_convenios_has_repasse_medico', function (Blueprint $table) {
            $table->foreignId('procedimento_instituicao_convenio_id')->references('id')->on('procedimentos_instituicoes_convenios')->index('procedimento_convenio_has_repasse_id');
            $table->foreignId('prestador_id')->references('id')->on('prestadores')->index('prestador_has_repasse_id');
            $table->string('tipo')->comment('dinheiro ou porcentagem');
            $table->decimal('valor_repasse', 8,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedimentos_convenios_has_repasse_medico');
    }
}
