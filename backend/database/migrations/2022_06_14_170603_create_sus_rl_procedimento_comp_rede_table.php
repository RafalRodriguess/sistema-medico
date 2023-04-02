<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusRlProcedimentoCompRedeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_rl_procedimento_comp_rede', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_PROCEDIMENTO', 10);
$table->string('CO_COMPONENTE_REDE', 10);


            $table->foreign('instituicoes_id', 'fk_sus_rl_procedimento_comp_rede_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_rl_procedimento_comp_rede');
    }
}
