<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbRegraCondicionadaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_regra_condicionada', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_REGRA_CONDICIONADA', 4);
$table->string('NO_REGRA_CONDICIONADA', 150);
$table->string('DS_REGRA_CONDICIONADA', 4000);


            $table->foreign('instituicoes_id', 'fk_sus_tb_regra_condicionada_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_regra_condicionada');
    }
}
