<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbOcupacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_ocupacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->char('CO_OCUPACAO',6);
$table->string('NO_OCUPACAO', 150);


            $table->foreign('instituicoes_id', 'fk_sus_tb_ocupacao_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_ocupacao');
    }
}
