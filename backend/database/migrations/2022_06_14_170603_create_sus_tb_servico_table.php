<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_servico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_SERVICO', 3);
$table->string('NO_SERVICO', 120);
$table->char('DT_COMPETENCIA',6);


            $table->foreign('instituicoes_id', 'fk_sus_tb_servico_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_servico');
    }
}
