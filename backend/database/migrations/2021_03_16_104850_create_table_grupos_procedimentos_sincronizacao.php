<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGruposProcedimentosSincronizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos_procedimentos_sincronizacao', function (Blueprint $table) {
            $table->id();
            $table->string('id_externo', '100');
            $table->foreignId('grupos_procedimentos_id')->references('id')->on('grupos_procedimentos')->index('grupos_procedimentos_id_index');
            $table->foreignId('instituicoes_id')->references('id')->on('instituicoes');
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
        Schema::dropIfExists('grupos_procedimentos_sincronizacao');
    }
}
