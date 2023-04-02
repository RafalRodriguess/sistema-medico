<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbComponenteRedeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_componente_rede', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_COMPONENTE_REDE', 10);
$table->string('NO_COMPONENTE_REDE', 150);
$table->string('CO_REDE_ATENCAO', 3);


            $table->foreign('instituicoes_id', 'fk_sus_tb_componente_rede_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_componente_rede');
    }
}
