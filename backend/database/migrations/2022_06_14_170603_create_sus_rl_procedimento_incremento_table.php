<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusRlProcedimentoIncrementoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_rl_procedimento_incremento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_PROCEDIMENTO', 10);
$table->string('CO_HABILITACAO', 4);
$table->unsignedInteger('VL_PERCENTUAL_SH');
$table->unsignedInteger('VL_PERCENTUAL_SA');
$table->unsignedInteger('VL_PERCENTUAL_SP');
$table->char('DT_COMPETENCIA',6);


            $table->foreign('instituicoes_id', 'fk_sus_rl_procedimento_incremento_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_rl_procedimento_incremento');
    }
}
