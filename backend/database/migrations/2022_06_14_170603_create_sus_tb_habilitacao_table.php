<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbHabilitacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_habilitacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_HABILITACAO', 4);
$table->string('NO_HABILITACAO', 150);
$table->char('DT_COMPETENCIA',6);


            $table->foreign('instituicoes_id', 'fk_sus_tb_habilitacao_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_habilitacao');
    }
}
