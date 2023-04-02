<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVinculoBrasindiceImportacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculo_brasindice_importacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
            $table->foreignId('tipo_id')->references('id')->on('vinculo_brasindice_tipos');

            $table->unsignedSmallInteger('edicao')->nullable();
            $table->date('vigencia')->nullable();

            $table->integer('insercoes', false, true)->default(0);
            $table->integer('atualizacoes', false, true)->default(0);

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
        Schema::dropIfExists('vinculo_brasindice_importacoes');
    }
}
