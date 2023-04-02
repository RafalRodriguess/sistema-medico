<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbGrupoHabilitacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_grupo_habilitacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('NU_GRUPO_HABILITACAO', 4);
$table->string('NO_GRUPO_HABILITACAO', 20);
$table->string('DS_GRUPO_HABILITACAO', 250);


            $table->foreign('instituicoes_id', 'fk_sus_tb_grupo_habilitacao_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_grupo_habilitacao');
    }
}
