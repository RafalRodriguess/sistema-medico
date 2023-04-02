<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableModeloReceituario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelo_receituarios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_prestador_id')->references('id')->on('instituicoes_prestadores');
            $table->string('descricao');
            $table->json('receituario');
            $table->string('estrutura')->comment('formulario - livre');
            $table->string('tipo')->comment('especial - simples');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modelo_receituarios');
    }
}
