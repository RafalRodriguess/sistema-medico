<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadesInternacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades_internacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('cc_id')
                ->references('id')->on('centros_de_custos');
            $table->integer('tipo_unidade');
            $table->string('localizacao');
            $table->boolean('hospital_dia');
            $table->boolean('ativo');
            $table->foreignId('instituicao_id')
                ->references('id')->on('instituicoes');
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
        Schema::dropIfExists('unidades_internacoes');
    }
}
