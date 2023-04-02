<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrigens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('origens', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->integer('tipo_id');
            $table->foreignId('cc_id')->references('id')->on('centros_de_custos');
            $table->boolean('ativo');
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
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
        Schema::dropIfExists('origens');
    }
}
